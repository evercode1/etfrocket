<?php

namespace App\Http\Controllers\User\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Auth\LoginService;
use App\Models\PersonalAccessToken;
use App\Models\User;
use App\Utilities\Auth;
use App\Http\Requests\RegistrationRequest;
use App\Services\Auth\RegistrationService;

class AuthController extends Controller
{

    public function register(RegistrationRequest $request, RegistrationService $register)
    {

        return $register->handleRegistration($request);
    }

    public function login(Request $request, LoginService $service)
    {

        $fields = $request->validate([

            'email' => 'required|string',
            'password' => 'required|string'

        ]);

        return $service->handleLogin($fields);
    }

    public function logout()
    {

        $user_id = Auth::id();

        // delete all tokens for user       

        PersonalAccessToken::where('tokenable_id', $user_id)->delete();

        return response()->json([

            'status' => 'success',
            'message' => 'Logged out'

        ]);
    }

    public function loginCheck(Request $request)
    {
        $request->validate([

            'token' => 'string|required'

        ]);

        $token = $request->input('token');
        $hashedToken = hash('sha256', $token);
        $accessToken = PersonalAccessToken::where('token', $hashedToken)->first();

        if (! $accessToken) {

            return response()->json([

                'exists' => false,
                'user' => null

            ]);
        }

        $user = User::find($accessToken->tokenable_id);

        return response()->json([

            'exists' => true,
            'user' => $user

        ]);
    }

    public function permissionDenied()
    {

        return response()->json([

            'status' => 'error',

            'message' => 'Permission denied'

        ], 422);
    }
}
