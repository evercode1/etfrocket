<?php

use Illuminate\Support\Facades\Route;
use App\Utilities\IncludeRoutes;

Route::get('/health/check', function() {

    return response()->json(['status' => 'OK', 'code' => 200, 'message' => 'healthy']);

});

/*
|--------------------------------------------------------------------------
| Admin ROUTES
|--------------------------------------------------------------------------
|
| routes for admin functionalities, requires authentication
|
*/

IncludeRoutes::file('routes/Admin/admin.php');


/*
|--------------------------------------------------------------------------
| Auth ROUTES
|--------------------------------------------------------------------------
|
| routes for authentication
|
*/

IncludeRoutes::file('routes/Auth/auth.php');


/*
|--------------------------------------------------------------------------
| User ROUTES
|--------------------------------------------------------------------------
|
| routes for user functionalities
|
*/

IncludeRoutes::file('routes/User/user.php');