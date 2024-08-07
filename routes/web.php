<?php

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
/*
Route::get('/', function () {
    return view('welcome');
});
*/

Route::get('/', 'LoginController@index')->name('dashboard.index');
Route::post('/autenticacion', 'LoginController@login')->name('dashboard.login');
Route::get('/cerrar-sesion', 'LoginController@logout')->name('dashboard.logout');

Route::middleware('auth')->group(function () {

    Route::get('/usuarios', 'UserController@index')->name('dashboard.user.index');
    Route::post('/usuarios-store', 'UserController@store')->name('dashboard.user.store');
    Route::post('/usuarios-eliminar', 'UserController@delete')->name('dashboard.user.delete');

    Route::get('/empresa', 'CompanyController@index')->name('dashboard.company.index');
    Route::post('/empresa-actualizar', 'CompanyController@update')->name('dashboard.company.update');


});
