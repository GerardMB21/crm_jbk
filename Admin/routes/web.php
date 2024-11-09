<?php

use Illuminate\Support\Facades\Auth;
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


Auth::routes();

Route::get('/', [App\Http\Controllers\HomeController::class, 'root']);

// esta ruta evita renderizar mas vistas
// Route::get('{any}', [App\Http\Controllers\HomeController::class, 'index']);
//Language Translation

Route::get('index/{locale}', [App\Http\Controllers\HomeController::class, 'lang']);

Route::post('/formsubmit', [App\Http\Controllers\HomeController::class, 'FormSubmit'])->name('FormSubmit');
Route::post('/save-company', [App\Http\Controllers\EnterpriseController::class, 'SaveCompany'])->name('SaveCompany');

Route::get('/profile', [App\Http\Controllers\ProfileController::class, 'index']);
Route::get('/enterprise', [App\Http\Controllers\EnterpriseController::class, 'index']);
Route::get('/hours', [App\Http\Controllers\HoursController::class, 'index']);

// Route::middleware('auth')->group(function () {

//   //ADMINISTRACION DE USUARIOS - USUARIOS
//   Route::get('/profile', [App\Http\Controllers\UserController::class, 'profile'])->name('profile');
//   Route::get('/usuarios', 'UserController@index')->name('dashboard.user.index');
//   Route::post('/usuarios-store', 'UserController@store')->name('dashboard.user.store');
//   Route::post('/usuarios-eliminar', 'UserController@delete')->name('dashboard.user.delete');
//   Route::post('/usuarios-listar-grupos', 'UserController@listGroup')->name('dashboard.user.list.group');
//   Route::post('/usuarios-eliminar-grupos', 'UserController@deleteGroup')->name('dashboard.user.delete.group');
//   Route::post('/usuarios-agregar-grupos', 'UserController@addGroup')->name('dashboard.user.add.group');

// });
