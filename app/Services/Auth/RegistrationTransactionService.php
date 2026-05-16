<?php

namespace App\Services\Auth;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Services\FailureLogs\LogFailureService;
use Illuminate\Support\Facades\Mail;
use App\Mail\VerifyEmail;
use App\Models\UserVerification;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class RegistrationTransactionService
{

    public function createUser(Request $request)
    {

        // Start transaction!

        DB::beginTransaction();

        try {

            // 1. Create the user record

            $user = User::create([

                'name' => $request->name,
                'email' => $request->email,
                'password' => $request->password,
                'email_verified_at' => NULL,


            ]);

            // 2. Generate and save verification token

            $verificationToken = Str::random(60);
            UserVerification::create([
                'user_id' => $user->id,
                'token' => $verificationToken,
                'created_at' => now(),
            ]);



            DB::commit();
        } catch (\Exception $e) {

            // Rollback transaction

            DB::rollback();


            (new LogFailureService)->logFailure($e, 'register_user', __CLASS__);

            throw new \RuntimeException('Oops! Something went wrong. Please try again later or contact support if the issue persists.');
        }

        // 3. Send the email

        Mail::to($user->email)
            ->send(new VerifyEmail($user, $verificationToken));

        Log::info('Sending email to: ' . $user->email);

        return $user;
    }
}
