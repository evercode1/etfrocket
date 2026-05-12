<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Dev\ExternalSeeders\MakeAdminUsersSeederController;
use App\Http\Controllers\Dev\ExternalSeeders\MakeSeedsController;


 /*
|--------------------------------------------------------------------------
| Seeder ROUTES
|--------------------------------------------------------------------------
|
| Routes for seeders.
|
*/

Route::group(['middleware' => ['allowSeeds']], function() {

    Route::get('/make-seeds', [MakeSeedsController::class, 'index']);
    Route::get('/make-admin-users', [MakeAdminUsersSeederController::class, 'run']);
    Route::get('/make-seed', [MakeSeedsController::class, 'makeSeed']);
    Route::get('/drop-seed', [MakeSeedsController::class, 'dropSeed']);


});

