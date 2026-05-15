<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\User\Portfolios\PortfoliosController;

/*
|--------------------------------------------------------------------------
| User Support ROUTES
|--------------------------------------------------------------------------
|
| Users that have access to these routes must be logged in.
|
*/

Route::group(['middleware' => ['auth:sanctum']], function () {


    Route::get('/list-portfolios', [PortfoliosController::class, 'listPortfolios']);

    Route::get('/view-portfolio/{id}', [PortfoliosController::class, 'viewPortfolio']);

    Route::get('/get-create-portfolio-form-config', [PortfoliosController::class,'getCreatePortfolioFormConfig']);

    Route::post('/create-portfolio', [PortfoliosController::class, 'createPortfolio']);

    Route::get('/get-update-portfolio-form-config/{id}', [PortfoliosController::class, 'getUpdatePortfolioFormConfig']);

    Route::put('/update-portfolio/{id}', [PortfoliosController::class, 'updatePortfolio']);

    Route::delete('/delete-portfolio/{id}', [PortfoliosController::class, 'deletePortfolio']);

});
