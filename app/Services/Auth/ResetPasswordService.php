<?php

namespace App\Services\Auth;

use App\Models\Employee\Employee;
use App\Models\Employee\EmployeePasswordReset;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ResetPasswordService
{

    public function passwordReset($request)
    {

        $request->validate([
            
            'password' => 'required|string|confirmed',
            'employee_id' => 'required|integer',
            'token' => 'required|string'
        
        ]);

        $passwordReset = EmployeePasswordReset::where('token', $request->token)->first();

        $id = $request->employee_id;

        $employee = Employee::find($id)->first();

        if ( ! $passwordReset->email == $employee->email ) {

            return response()->json([
            
            "status" => "error",
            "message" => 'invalid credentials',
            
            ], 401);

        }

        $password = Hash::make($request->password);

        // eloquent wouldn't update password, had to use DB

        DB::table('Employee.employees')
            
            ->where('id',$id )
              
            ->update(['password' => $password]);

        // delete token

        $oldToken = $request->token;

        if ( EmployeePasswordReset::where('token', $oldToken)->exists() ){

            $oldToken = EmployeePasswordReset::where('token', $oldToken)->first();

            $oldToken->delete();

        }

        return response()->json(['message' => 'Your Password has been updated', 201]);

    }

}