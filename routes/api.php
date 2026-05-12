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
| Admin Support ROUTES
|--------------------------------------------------------------------------
|
| routes for admin functionalities, requires authentication
|
*/

IncludeRoutes::file('routes/Admin/Support/admin-support.php');


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

/*
|--------------------------------------------------------------------------
| Settings ROUTES
|--------------------------------------------------------------------------
|
| routes for user settings, requires authentication
|
*/

IncludeRoutes::file('routes/User/Settings/settings.php');


/*
|--------------------------------------------------------------------------
| Support ROUTES
|--------------------------------------------------------------------------
|
| routes for user support, requires authentication
|
*/

IncludeRoutes::file('routes/User/Support/support.php');