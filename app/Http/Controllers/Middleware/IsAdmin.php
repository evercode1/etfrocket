<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Utilities\Auth;

class IsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    
    public function handle(Request $request, Closure $next): Response
    {
        
        if ($request->user() &&  ! $this->isAdmin()){

            return response()->json(['code' => 401, 'message' => 'Unauthorized'], 401);
        }

        return $next($request);

    }

    private function isAdmin()
    {

        return Auth::user()->is_admin == 1;

    }

}

