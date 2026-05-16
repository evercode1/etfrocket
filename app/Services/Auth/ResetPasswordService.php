<?php

namespace App\Services\Auth;

use App\Models\User;
use App\Models\PasswordResetToken;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ResetPasswordService
{

    public function passwordReset($request)
    {

        $request->validate([

            'password' => 'required|string|confirmed',
            'user_id' => 'required|integer',
            'token' => 'required|string'

        ]);

        $passwordReset = PasswordResetToken::where('token', $request->token)->first();

        $id = $request->user_id;

        $user = User::find($id);

        if ($passwordReset->email !== $user->email) {

            return response()->json([

                "status" => "error",
                "message" => 'invalid credentials',

            ], 401);
        }

        $password = Hash::make($request->password);

        // eloquent wouldn't update password, had to use DB

        DB::table('users')

            ->where('id', $id)

            ->update(['password' => $password]);

        // delete token

        $oldToken = $request->token;

        if (PasswordResetToken::where('token', $oldToken)->exists()) {

            $oldToken = PasswordResetToken::where('token', $oldToken)->first();

            $oldToken->delete();
        }

        return response()->json(['message' => 'Your Password has been updated'], 201);
    }
}
