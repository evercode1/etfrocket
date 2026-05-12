<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\User\Support\UserSupportController;

/*
|--------------------------------------------------------------------------
| User Support ROUTES
|--------------------------------------------------------------------------
|
| Users that have access to these routes must be logged in.
|
*/

Route::group(['middleware' => ['auth:sanctum']], function () {


    // Support

    Route::get('/my-support-tickets', [UserSupportController::class, 'index']);
    Route::get('/my-support-ticket/{id}', [UserSupportController::class, 'show']);
    Route::get('/my-support-response', [UserSupportController::class, 'showResponse']);
    Route::post('/mark-support-response-as-read', [UserSupportController::class, 'markAsRead']);
    Route::get('/new-support-ticket-form', [UserSupportController::class, 'newTicketFormConfig']);
    Route::post('/create-support-ticket', [UserSupportController::class, 'store']);
    Route::get('/new-support-response-to-ticket-form', [UserSupportController::class, 'newResponseFormConfig']);
    Route::post('/respond-to-support-response', [UserSupportController::class, 'respondToSupport']);
   

});
