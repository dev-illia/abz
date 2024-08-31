<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class TokenController extends Controller
{
    public function getToken()
    {
        $token = Str::random(60);
        $expiresAt = Carbon::now()->addMinutes(40);

        Cache::put('registration_token', [
            'token' => $token,
            'expires_at' => $expiresAt
        ], 40 * 60);

        return response()->json([
            'success' => true,
            'token' => $token,
        ]);
    }
}
