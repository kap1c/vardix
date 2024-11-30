<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;

Route::get('/', function (){
    return redirect('/posts');
});

Route::resource('posts', PostController::class);

// Inertia не захотела с PUT работать
Route::post('post/{post}', [PostController::class, 'update']);
