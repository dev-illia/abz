<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Position;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    
    public function showRegistrationForm()
    {
        $positions = Position::all();
    
        return view('auth.register', compact('positions'));
    }
    
    public function registerUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|min:2|max:60',
            'email' => 'required|email:rfc|unique:users,email',
            'phone' => 'required|regex:/^\+380\d{9}$/',
            'position_id' => 'required|integer|exists:positions,id',
            'photo' => 'required|image|mimes:jpg,jpeg|max:5120',
            'password' => 'required|string|min:6',
        ]);
    
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
    
        $existingUser = User::where('email', $request->email)
            ->orWhere('phone', $request->phone)
            ->first();
    
        if ($existingUser) {
            return back()->withErrors(['email' => 'User with this email or phone already exists.'])->withInput();
        }
        
    
        $data = $request->only(['name', 'email', 'phone', 'position_id']);
        $data['password'] = Hash::make($request->password);
    
        if ($request->hasFile('photo')) {
            \Tinify\setKey(env('TINIFY_API_KEY'));

            $photoPath = $request->file('photo')->store('photos/originals', 'public');
            $source = \Tinify\fromFile(storage_path('app/public/' . $photoPath));
            $optimizedPath = str_replace('originals', 'optimized', $photoPath);
            
            $resized = $source->resize(array(
                "method" => "cover",
                "width" => 70,
                "height" => 70
            ));
            $resized->toFile(storage_path('app/public/' . $optimizedPath));
            
            $data['photo'] = $optimizedPath;
        }
    
        User::create($data);
    
        session()->flash('success', 'New user successfully registered');
    
        
        return redirect()->route('register.form');
    }

    public function registerUserApi(Request $request)
    {
        $token = $request->header('Token');
        $cachedToken = Cache::get('registration_token');

        if (!$cachedToken || $cachedToken['token'] !== $token || $cachedToken['expires_at'] < now()) {
            return response()->json([
                'success' => false,
                'message' => 'The token expired.'
            ], 401);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|min:2|max:60',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|regex:/^\+380\d{9}$/',
            'position_id' => 'required',
            'photo' => 'required|image|mimes:jpg,jpeg|max:5120|dimensions:min_width=70,min_height=70',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'fails' => $validator->errors()->messages(),
            ], 422);
        }

        $existingUser = User::where('email', $request->email)
            ->orWhere('phone', $request->phone)
            ->first();

        if ($existingUser) {
            return response()->json([
                'success' => false,
                'message' => 'User with this phone or email already exist',
            ], 409);
        }

        $data = $request->only(['name', 'email', 'phone', 'position_id']);
        $data['password'] = Hash::make($request->password);

        if ($request->hasFile('photo')) {
            \Tinify\setKey(env('TINIFY_API_KEY'));

            $photoPath = $request->file('photo')->store('photos/originals', 'public');
            $source = \Tinify\fromFile(storage_path('app/public/' . $photoPath));
            $optimizedPath = str_replace('originals', 'optimized', $photoPath);
            
            $resized = $source->resize(array(
                "method" => "cover",
                "width" => 70,
                "height" => 70
            ));
            $resized->toFile(storage_path('app/public/' . $optimizedPath));
            
            $data['photo'] = $optimizedPath;
        }

        $user = User::create($data);

        return response()->json([
            'success' => true,
            'user_id' => $user->id,
            'message' => 'New user successfully registered',
        ], 201);
    }
}

