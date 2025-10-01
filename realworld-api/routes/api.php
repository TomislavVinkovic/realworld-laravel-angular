<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::middleware('anonymous')->group(function() {
    Route::controller(AuthController::class)
        ->prefix('users')
        ->group(
            function() {
                Route::post('login', 'login');
                Route::post('', 'register');
            }
        );
});
    

Route::get('profiles/{user}', [ProfileController::class, 'show']);

Route::controller(ArticleController::class)
    ->prefix('articles')
    ->group(
        function() {
            Route::get('', 'index');
            Route::get('{article}', 'show');
        }
    );

Route::middleware('jwt')->group(function() {
    Route::controller(AuthController::class)
        ->prefix('user')
        ->group(
            function() {
                Route::get('', 'show');
                Route::get('logout', 'logout');
                Route::put('', 'update');
            }
        );

    Route::get('profiles/{user}/follow', [ProfileController::class, 'follow']);

    Route::controller(ArticleController::class)
        ->prefix('articles')
        ->group(
            function() {
                Route::get('feed', 'feed');
                Route::post('', 'store');
                Route::put('{article}', 'update');
                Route::delete('{article}', 'destroy');
            }
        ); 
}); 