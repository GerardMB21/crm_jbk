<?php

use App\Models\Campain;
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

    //NAVEGACION - CAMPAÑAS
    Route::post('/listar-campanias', 'CampainController@list')->name('dashboard.campain.list');

    //ADMINISTRACION DE USUARIOS - USUARIOS
    Route::get('/usuarios', 'UserController@index')->name('dashboard.user.index');
    Route::post('/usuarios-store', 'UserController@store')->name('dashboard.user.store');
    Route::post('/usuarios-eliminar', 'UserController@delete')->name('dashboard.user.delete');
    Route::post('/usuarios-listar-grupos', 'UserController@listGroup')->name('dashboard.user.list.group');
    Route::post('/usuarios-eliminar-grupos', 'UserController@deleteGroup')->name('dashboard.user.delete.group');
    Route::post('/usuarios-agregar-grupos', 'UserController@addGroup')->name('dashboard.user.add.group');

    //ADMINISTRACION DE USUARIOS - GRUPOS DE USUARIOS
    Route::get('/grupo-usuarios', 'GroupUserController@index')->name('dashboard.group.user.index');
    Route::post('/grupo-eliminar-usuarios', 'GroupUserController@delete')->name('dashboard.group.user.delete');
    Route::post('/grupo-eliminar-store', 'GroupUserController@store')->name('dashboard.group.user.store');

    //CONFIGURACION DE ASISTENCIA - HORARIOS
    Route::get('/horarios', 'HorarioController@index')->name('dashboard.horario.index');
    Route::post('/horarios-store', 'HorarioController@store')->name('dashboard.horario.store');
    Route::post('/horarios-eliminar', 'HorarioController@delete')->name('dashboard.horario.delete');
    Route::post('/horarios-buscar-dias', 'HorarioController@getDay')->name('dashboard.horario.get.day');

    //CONFIGURACION DE EMPRESA - MI EMPRESA
    Route::get('/empresa', 'CompanyController@index')->name('dashboard.company.index');
    Route::post('/empresa-actualizar', 'CompanyController@update')->name('dashboard.company.update');

    //CONFIGURACION DE CAMPAÑAS - PESTAÑAS DE ESTADO
    Route::get('/pestaña-estado', 'TabStateController@index')->name('dashboard.tab_state.index');
    Route::post('/pestaña-estado/listar-estados', 'TabStateController@list')->name('dashboard.tab_state.list');
    Route::post('/pestaña-estado/almacenar', 'TabStateController@store')->name('dashboard.tab_state.store');
    Route::post('/pestaña-estado/editar-pestania', 'TabStateController@getTabState')->name('dashboard.tab_state.get_tab_state');
    Route::post('/pestaña-estado/eliminar-pestania', 'TabStateController@delete')->name('dashboard.tab_state.delete');
    Route::post('/pestaña-estado/deshabilitar-pestania', 'TabStateController@deshabilitar')->name('dashboard.tab_state.deshabilitar');
    Route::post('/pestaña-estado/habilitar-pestania', 'TabStateController@habilitar')->name('dashboard.tab_state.habilitar');

    //CONFIGURACION DE CAMPAÑAS - ESTADOS
    Route::get('/estado', 'StateController@index')->name('dashboard.state.index');
    Route::post('/estado/listar-estados', 'StateController@list')->name('dashboard.state.list');
    Route::post('/estado/almacenar', 'StateController@store')->name('dashboard.state.store');
    //Route::post('/estado/editar-pestania', 'StateController@getTabState')->name('dashboard.state.get_state');
    Route::post('/estado/eliminar-pestania', 'StateController@delete')->name('dashboard.state.delete');
    Route::post('/estado/deshabilitar-pestania', 'StateController@deshabilitar')->name('dashboard.state.deshabilitar');
    Route::post('/estado/habilitar-pestania', 'StateController@habilitar')->name('dashboard.state.habilitar');
    Route::post('/estado/listar-pestania', 'StateController@getTabState')->name('dashboard.state.get_tab_state');
    Route::post('/estado/listar-estado', 'StateController@getState')->name('dashboard.state.get_state');

    //CONFIGURACION DE CAMPAÑAS - ESTADOS
    Route::get('/bloque-de-campos', 'BlockController@index')->name('dashboard.block.index');
    Route::post('/bloque-de-campos/almacenar', 'BlockController@store')->name('dashboard.block.store');
    Route::post('/bloque-de-campos/listar-bloques', 'BlockController@list')->name('dashboard.block.list');
    Route::post('/bloque-de-campos/bloque', 'BlockController@getBlock')->name('dashboard.block.get_block');
    Route::post('/bloque-de-campos/eliminar', 'BlockController@delete')->name('dashboard.block.delete');
    Route::post('/bloque-de-campos/deshabilitar', 'BlockController@deshabilitar')->name('dashboard.block.deshabilitar');
    Route::post('/bloque-de-campos/habilitar', 'BlockController@habilitar')->name('dashboard.block.habilitar');
});
