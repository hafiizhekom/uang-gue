<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\UserSetupService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{

    public function handleGoogleAuth(Request $request, UserSetupService $setupService)
    {

        $idToken = $request->id_token;

        if (!$idToken) {
            return response()->json(['message' => 'ID Token is required'], 400);
        }

        // 1. Verifikasi ke Google API
        $response = Http::get("https://oauth2.googleapis.com/tokeninfo?id_token={$idToken}");

        if ($response->failed()) {
            return response()->json(['message' => 'Token tidak valid atau expired'], 401);
        }

        $googleUser = $response->json();

        // 2. SECURITY CHECK: Pastikan token ini emang buat App Uangku!
        // Kita cek 'aud' (Audience) di token harus sama dengan Client ID kita.
        if ($googleUser['aud'] !== config('services.google.client_id')) {
            return response()->json([
                'message' => 'Security Alert: Token audience mismatch!'
            ], 403);
        }

        // 3. Cari atau Buat User
        $user = User::updateOrCreate(
            ['email' => $googleUser['email']],
            [
                'name'      => $googleUser['name'],
                'google_id' => $googleUser['sub'],
                'avatar'    => $googleUser['picture'] ?? null,
                // Password random karena login via Google
                'password'  => Hash::make(Str::random(32)), 
            ]
        );

        // 4. JALANKAN SETUP (Starter Pack: Period, Payment, Categories, Template)
        if ($user->wasRecentlyCreated) {
            $setupService->initializeNewUser($user);
        }

        // 5. Generate Internal Token (Sanctum)
        $token = $user->createToken('auth_token')->plainTextToken;

        Log::info("User {$user->email} authenticated via Google. Token generated.");

        return response()->json([
            'access_token' => $token,
            'user'         => $user,
            'is_new_user'  => $user->wasRecentlyCreated
        ]);
    }
    // POST /api/register
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $user,
        ], 201);
    }

    // POST /api/login
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'The provided credentials are incorrect.',
                'errors' => [
                    'email' => ['The provided credentials are incorrect.']
                ]
            ], 422);
        }

        $token = $user->createToken('auth_token')->plainTextToken;
        
        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $user,
        ]);
    }

    // POST /api/logout
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logged out successfully'
        ]);
    }

    // GET /api/me
    public function me(Request $request)
    {
        return response()->json($request->user());
    }
}
