<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function showAllUsers(Request $request)
    {
        $perPage = 6;
        $users = User::paginate($perPage);

        return view('users.all', compact('users'));
    }
    
    public function showUser(string $id)
    {
        $user = User::find($id);

        if (!$user) {
            abort(404, 'User not found');
        }

        return view('users.one', ['user' => $user]);
    }
    
    
    public function getAllUsersApi(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'page' => 'integer|min:1',
            'count' => 'integer|min:1'
        ]);

        $page = $request->query('page', 1);
        $count = $request->query('count', 10);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'fails' => $validator->errors()->messages()
            ], 422);
        }

        $users = User::with('position')->paginate($count, ['*'], 'page', $page);

        if ($page < 1 || $page > $users->lastPage()) {
            return response()->json([
                'success' => false,
                'message' => 'Page not found'
            ], 404);
        }

        $nextPageUrl = $users->nextPageUrl() ? $users->nextPageUrl() . '&count=' . $count : null;
        $prevPageUrl = $users->previousPageUrl() ? $users->previousPageUrl() . '&count=' . $count : null;

        return response()->json([
            'success' => true,
            'total_pages' => $users->lastPage(),
            'total_users' => $users->total(),
            'count' => $users->perPage(),
            'page' => $users->currentPage(),
            'links' => [
                'next_url' => $nextPageUrl,
                'prev_url' => $prevPageUrl,
            ],
            'users' => $users->map(function ($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'phone' => $user->phone,
                    'position' => $user->position->name,
                    'position_id' => $user->position_id,
                    'photo' => $user->photo
                ];
            })
        ]);
    }

    public function getUserByIdApi($id)
    {
        $validator = Validator::make(['userId' => $id], [
            'userId' => 'required|integer'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'The user with the requested id does not exist',
                'fails' => $validator->errors()->messages()
            ], 400);
        }

        $user = User::with('position')->find($id);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'position' => $user->position->name,
                'position_id' => $user->position_id,
                'photo' => $user->photo
            ]
        ]);
    }
}
