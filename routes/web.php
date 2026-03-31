<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\postsController;

Route::get('/', function () {
      return view('welcome');
}); 
Route::get('/posts',[postsController::class,'indexe'])->name('posts.indexe');

Route::get('/posts/create',[postsController::class,'create'])->name('posts.create'); 
Route::post('/posts', [postsController::class, 'store'])->name('posts.store');
Route::get('/posts/{post}',[postsController::class,'show'])->name('posts.show');

Route::get('/posts/{post}/edit',[postsController::class,'edit'])->name('posts.edit');