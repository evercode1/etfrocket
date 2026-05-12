<?php

namespace App\Services\Settings;

use App\Models\User;
use Illuminate\Validation\Rule;
use App\Utilities\Auth;
use Illuminate\Http\Request;

class UpdateUserNameService
{

    public static function updateUserName(Request $request)
    {

        $id = Auth::id();

        $request->validate([

            'name' => [
                'required',
                'string',
                Rule::unique('users')->ignore($id)
            ]

        ]);


        $user = User::where('id', $id)->first();

        $user->name = $request->name;

        $user->save();

        return response()->json(
            [
                'status' => 'success',
                'message' => 'your settings have been updated.'
            ],
            200
        );
    }
}
