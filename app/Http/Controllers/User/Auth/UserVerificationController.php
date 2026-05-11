<?php

namespace App\Http\Controllers\User\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Auth\VerifyAccountService;
use App\Services\Auth\RequestVerificationService;

class UserVerificationController extends Controller
{   

    public function verifyAccount(string $token, VerifyAccountService $service)
    {

        return $service->verifyAccount($token);    
        
    }

    public function requestVerificationToken(Request $request, RequestVerificationService $service)
    {

        return $service->requestVerification($request);

    }
    
}
