<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;


///////////////////////////////////  Authentication Route ///////////////////////////////////
Route::controller(\App\Http\Controllers\AuthenticationController::class)->group(function () {
    Route::middleware('guest')->group(function () {
        Route::post('register', 'register');
        Route::post('login', 'login');
        Route::post('/resetPassword', 'resetPassword');
        Route::prefix('socialite')->group(function () {
            Route::get('{provider}/login', 'socialiteLogin');
            Route::get('{provider}/redirect', 'socialiteRedirect');
        });
    });
    Route::middleware('auth:sanctum')->prefix('user')->group(function () {
        Route::PUT('/update', 'update');
        Route::get('/logout', 'logout');
        Route::get('/logoutAllDevice', 'logoutAllDevice');
        Route::delete('/delete', 'delete');
    });
});
///////////////////////////////////   End Authentication Route ///////////////////////////////////


///////////////////////////////////  User  Route ///////////////////////////////////
Route::controller(UserController::class)->prefix('user')->middleware('auth:sanctum')->group(function () {
    Route::post('/search', 'search');
    Route::get('/showCurrentUser', 'showCurrentUser');
    Route::get('/show/{id}', 'show');
    Route::get('/friends', 'friends');
    Route::get('/news_feed', 'newsFeed');
});
///////////////////////////////////  End User Route ///////////////////////////////////


///////////////////////////////////   friendRequest Route  ///////////////////////////////////
Route::controller(\App\Http\Controllers\FriendRequestController::class)->middleware('auth:sanctum')->prefix('requests')->group(function () {
    Route::get('/sendRequest/{id}', 'sendRequest');
    Route::delete('/removeRequest/{id}', 'removeRequest');
    Route::get('/acceptRequest/{id}', 'acceptRequest');
    Route::get('/rejectRequest/{id}', 'rejectRequest');
    Route::get('/friends', 'friends');
});
///////////////////////////////////  End FriendRequest Route  ///////////////////////////////////


///////////////////////////////////   Chat Route  ///////////////////////////////////
Route::controller(\App\Http\Controllers\ChatController::class)->middleware('auth:sanctum')->prefix('chat')->group(function () {
    Route::post('/sendMessage/{id}', 'sendMessage');
    Route::get('/showMessage/{id}', 'showMessage');
    Route::DELETE('/removeMessage/{id}', 'removeMessage');
});
///////////////////////////////////  End Chat Route  ///////////////////////////////////


///////////////////////////////////   Post Route  ///////////////////////////////////
Route::controller(\App\Http\Controllers\PostController::class)->middleware('auth:sanctum')->prefix('post')->group(function () {
    Route::post('/create', 'create');
    Route::PUT('/update/{id}', 'update');
    Route::delete('/delete/{id}', 'delete');
});
///////////////////////////////////  End Post Route  ///////////////////////////////////


///////////////////////////////////   Comments Route  ///////////////////////////////////
Route::controller(\App\Http\Controllers\CommentController::class)->middleware('auth:sanctum')->prefix('comment')->group(function () {
    Route::post('/create/{id}', 'create');
    Route::PUT('/update/{id}', 'update');
    Route::delete('/delete/{id}', 'delete');
    Route::get('/post/{id}', 'show');

});
///////////////////////////////////  End Comments Route  ///////////////////////////////////


///////////////////////////////////   Interaction Route  ///////////////////////////////////
Route::controller(\App\Http\Controllers\InteractionController::class)->middleware('auth:sanctum')->prefix('interaction')->group(function () {
    Route::post('/create/{id}', 'create');
    Route::delete('/delete/{id}', 'delete');
    Route::get('/post/{id}', 'show');
});
///////////////////////////////////  End Interaction Route  ///////////////////////////////////


///////////////////////////////////   Interaction Route  ///////////////////////////////////
Route::controller(\App\Http\Controllers\SharePostController::class)->middleware('auth:sanctum')->prefix('sharePost')->group(function () {
    Route::post('/create/{id}', 'create');
    Route::put('/update/{id}', 'update');
    Route::delete('/delete/{id}', 'delete');
});
///////////////////////////////////  End Interaction Route  ///////////////////////////////////


