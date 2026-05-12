<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\Support\SupportController;

/*
|--------------------------------------------------------------------------
| ADMIN SUPPORT ROUTES
|--------------------------------------------------------------------------
|
| Users that have access to these routes must be Admin and logged in.
|
*/

Route::group(['middleware' => ['auth:sanctum', 'isAdmin']], function() {

    // Manage Users

   
    

    // Support

    Route::get('/get-support-tickets', [SupportController::class, 'index']);
    Route::get('/support-ticket/{id}', [SupportController::class, 'show']);
    Route::get('/get-support-reply-form', [SupportController::class, 'getSupportReplyFormConfig']);
    Route::post('/support-reply-to-ticket', [SupportController::class, 'supportReplyToTicket']);
    Route::post('/close-ticket', [SupportController::class, 'closeTicket']);


});