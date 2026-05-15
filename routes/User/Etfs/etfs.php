<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\User\Etfs\EtfsFilterController;
use App\Http\Controllers\User\Etfs\EtfsListController;

/*
|--------------------------------------------------------------------------
| User Support ROUTES
|--------------------------------------------------------------------------
|
| Users that have access to these routes must be logged in.
|
*/

Route::group(['middleware' => ['auth:sanctum']], function () {


    Route::get('/get-etf-filters', [EtfsFilterController::class, 'getFilters']);

    Route::get('/list-etfs', [EtfsListController::class, 'listEtfs']);
   

});
