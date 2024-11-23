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

Route::post('/enterprise/save-company', [App\Http\Controllers\EnterpriseController::class, 'SaveCompany'])->name('SaveCompany');
Route::post('/enterprise/save-hour', [App\Http\Controllers\HoursController::class, 'SaveHour'])->name('SaveHour');
Route::post('/enterprise/save-group', [App\Http\Controllers\GroupUsersController::class, 'SaveGroup'])->name('SaveGroup');
Route::post('/enterprise/save-user', [App\Http\Controllers\UsersController::class, 'SaveUser'])->name('SaveUser');
Route::post('/enterprise/add-group-user', [App\Http\Controllers\UsersController::class, 'AddGroup'])->name('AddGroup');
Route::post('/enterprise/save-advertisement', [App\Http\Controllers\AdvertisementsController::class, 'SaveAdvertisement'])->name('SaveAdvertisement');

Route::delete('/enterprise/delete-hour/{id}', [App\Http\Controllers\HoursController::class, 'DeleteHour'])->name('DeleteHour');
Route::delete('/enterprise/delete-group/{id}', [App\Http\Controllers\GroupUsersController::class, 'DeleteGroup'])->name('DeleteGroup');
Route::delete('/enterprise/delete-user/{id}', [App\Http\Controllers\UsersController::class, 'DeleteUser'])->name('DeleteUser');
Route::delete('/enterprise/delete-user-group/{id}', [App\Http\Controllers\UsersController::class, 'DeleteUserGroup'])->name('DeleteUserGroup');
Route::delete('/enterprise/delete-advertisement/{id}', [App\Http\Controllers\AdvertisementsController::class, 'DeleteAdvertisement'])->name('DeleteAdvertisement');

Route::get('/profile', [App\Http\Controllers\ProfileController::class, 'index']);
Route::get('/enterprise/config', [App\Http\Controllers\EnterpriseController::class, 'index']);
Route::get('/enterprise/hours', [App\Http\Controllers\HoursController::class, 'index']);
Route::get('/enterprise/groups', [App\Http\Controllers\GroupUsersController::class, 'index']);
Route::get('/enterprise/users', [App\Http\Controllers\UsersController::class, 'index']);
Route::get('/enterprise/advertisements', [App\Http\Controllers\AdvertisementsController::class, 'index']);

Route::post('/sales/save-campaign', [App\Http\Controllers\CampaignsController::class, 'SaveCampaign'])->name('SaveCampaign');

Route::delete('/sales/delete-campaign/{id}', [App\Http\Controllers\CampaignsController::class, 'DeleteCampaign'])->name('DeleteCampaign');

Route::get('/sales/campaigns', [App\Http\Controllers\CampaignsController::class, 'index']);
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
