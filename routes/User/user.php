<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| User ROUTES
|--------------------------------------------------------------------------
|
| Users that have access to these routes must be logged in.
|
*/

Route::group(['middleware' => ['auth:sanctum']], function() {


    

});