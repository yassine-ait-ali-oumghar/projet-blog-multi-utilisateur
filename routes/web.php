<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\postsController;

Route::get('/', function () {
      return view('welcome');
}); 
Route::get('/posts',[postsController::class,'indexe']);
Route::get('/posts/{post}',[postsController::class,'show']);
