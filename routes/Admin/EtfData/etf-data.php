<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\ExternalData\ExternalDataController;

/*
|--------------------------------------------------------------------------
| 
|
| Admin Etf Data ROUTES
|--------------------------------------------------------------------------
|
|
|
*/

Route::group(['middleware' => ['allowExternalData']], function() {

    Route::post('/etf-data', [ExternalDataController::class, 'updateEtfData']);

});
