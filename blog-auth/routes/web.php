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

// Összes poszt
Route::get('/', [HomeController::class, 'index'])->name('home');

// Egy adott poszt
Route::get( '/posts/{postId}', [PostController::class, 'postIndex'])->name('post')->where(['postId' => '[0-9]+']);

// Egy kategóriába tartozó összes poszt
Route::get( '/categories/{categoryId}', [CategoryController::class, 'categoryId'])->name('category')->where(['categoryId' => '[0-9]+']);

Route::middleware(['auth'])->group(function () {
    // Védett végpontok, csak bejelentkezve lehet elérni őket.
    Route::get(   '/posts/create',        [PostController::class, 'newPostIndex'])->name('new-post');
    Route::post(  '/posts/create',        [PostController::class, 'storeNewPost'])->name('store-new-post');
    Route::get(   '/posts/edit/{postId}', [PostController::class, 'editPostIndex'])->name('edit-post')->where(['postId' => '[0-9]+']);
    Route::put(   '/posts/edit/{postId}', [PostController::class, 'storeEditedPost'])->name('store-edited-post')->where(['postId' => '[0-9]+']);
    Route::delete('/posts/{postId}',      [PostController::class, 'deletePost'])->name('delete-post')->where(['postId' => '[0-9]+']);


    Route::get( '/categories/create',   [CategoryController::class, 'newCategoryIndex'])->name('new-category');
    Route::post('/categories/create',   [CategoryController::class, 'storeNewCategory'])->name('store-new-category');
});


// authentikáció végpontjai
require __DIR__.'/auth.php';
