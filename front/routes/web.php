<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Auth\AddUserController;
use App\Http\Controllers\Auth\EditUserController;
use App\Http\Controllers\Auth\DeleteUserController;

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

Route::get('/', [HomeController::class, 'index']);

Route::get('/papers', [HomeController::class, 'papers'])->name('papers');
Route::get('/authors', [HomeController::class, 'authors'])->name('authors');
Route::get('/search', [HomeController::class, 'search'])->name('search');

Route::get('/search/abstract', [HomeController::class, 'searchabstract'])->name('searchAbstract');
Route::get('/search/authors', [HomeController::class, 'searchauthors'])->name('searchAuthors');
Route::get('/search/papers', [HomeController::class, 'searchpapers'])->name('searchPapers');


Route::get('/introduction', [HomeController::class, 'introduction'])->name('introduction');
Route::get('/method', [HomeController::class, 'method'])->name('method');
Route::get('/credits', [HomeController::class, 'credits'])->name('credits');


Route::get('/view/{id}', [HomeController::class, 'view']);
Route::get('/view/{id}', [HomeController::class, 'view']);
Route::get('/author/{id}', [HomeController::class, 'author']);

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/topics/{base?}', [DashboardController::class, 'topicModels'])->name('topics');
    Route::get('/topic/{topic_id}', [DashboardController::class, 'editTopic'])->name('editTopic');
    Route::post('/topic/{topic_id}', [DashboardController::class, 'saveTopic'])->name('saveTopic');
    Route::middleware(['admin'])->group(function () {
        Route::get('/users', [UserController::class, 'index'])->name('users_list');
        Route::get('/users/add', [AddUserController::class, 'add'])->name('add_user');
        Route::post('/users/add', [AddUserController::class, 'store'])->name('store_user');

        Route::get('/users/edit/{user_id}', [EditUserController::class, 'edit'])->name('edit_user');
        Route::post('/users/edit/infos/{user_id}', [EditUserController::class, 'saveInfos'])->name('save_user_infos');
        Route::post('/users/edit/password/{user_id}', [EditUserController::class, 'savePassword'])->name('save_user_password');

        Route::delete('/users/delete/{user_id}', [DeleteUserController::class, 'delete'])->name('delete-user');
    });
});

require __DIR__ . '/auth.php';
