<?php

namespace App\Utilities;

class Auth
{

    // Avoids having to type auth()->id() and auth()->user() everywhere, so we can use Auth::id() and Auth::user() instead.

    public static function id()
    {

        // keeps the ide from complaining
        /** @var \Illuminate\Contracts\Auth\Guard|\Illuminate\Contracts\Auth\StatefulGuard $auth */

        $auth = auth();

        return $auth->id();
    }

    public static function user()
    {

        // keeps the ide from complaining
        /** @var \Illuminate\Contracts\Auth\Guard|\Illuminate\Contracts\Auth\StatefulGuard $auth */

        $auth = auth();

        return $auth->user();
    }
}
