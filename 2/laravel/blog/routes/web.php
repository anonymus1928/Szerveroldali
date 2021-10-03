<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

// Route::get('/kategoria/{categoryId}/{postId?}', [CategoryController::class, 'categoryIndex'])->name('category')->where(['categoryId' => '[0-9]+', 'postId' => '[a-z]+']);

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/categories',        [CategoryController::class, 'categoryIndex'])   ->name('category');
Route::get('/categories/create', [CategoryController::class, 'newCategoryIndex'])->name('new-category');

Route::get( '/posts/create', [PostController::class, 'newPostIndex'])->name('new-post');
Route::post('/posts/create', [PostController::class, 'storeNewPost'])->name('store-new-post');
