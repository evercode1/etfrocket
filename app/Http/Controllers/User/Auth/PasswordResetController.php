<?php

namespace App\Http\Controllers\User\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Auth\PasswordResetFormService;
use App\Services\Auth\RequestPasswordResetTokenService;
use App\Services\Auth\ResetPasswordService;


class PasswordResetController extends Controller
{
    public function requestPasswordResetToken(Request $request, RequestPasswordResetTokenService $service)
    {

        $request->validate([

            'email' => 'required|email'

        ]);

       return $service->requestResetToken($request); 

    }

    public function getPasswordResetForm(string $token, PasswordResetFormService $service)
    {

        return $service->getPasswordResetForm($token);

    }


    public function passwordReset(Request $request, ResetPasswordService $service)
    {

        return $service->passwordReset($request);

    }

}
