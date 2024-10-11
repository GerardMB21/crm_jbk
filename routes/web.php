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
    Route::get('/grupo-usuarios/grupo', 'GroupUserController@getGroup')->name('dashboard.group.user.getGroup');
    Route::post('/grupo-eliminar-usuarios', 'GroupUserController@delete')->name('dashboard.group.user.delete');
    Route::post('/grupo-eliminar-store', 'GroupUserController@store')->name('dashboard.group.user.store');

    //CONFIGURACION DE ASISTENCIA - HORARIOS
    Route::get('/horarios', 'HorarioController@index')->name('dashboard.horario.index');
    Route::post('/horarios-store', 'HorarioController@store')->name('dashboard.horario.store');
    Route::post('/horarios-eliminar', 'HorarioController@delete')->name('dashboard.horario.delete');
    Route::post('/horarios-buscar-dias', 'HorarioController@getDay')->name('dashboard.horario.get.day');

    //CONFIGURACION DE EMPRESA - MI EMPRESA
    Route::get('/empresa', 'CompanyController@index')->name('dashboard.company.index');
    Route::get('/empresa/ver-logo', 'CompanyController@getLogo')->name('dashboard.company.getLogo');
    Route::get('/empresa/paleta-de-colores', 'CompanyController@getColorsPallette')->name('dashboard.company.getColorsPallette');
    Route::get('/empresa/logo/{fileName}', 'CompanyController@files')->name('dashboard.company.files');
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

    //CONFIGURACION DE CAMPAÑAS - BLOQUE DE CAMPOS
    Route::get('/bloque-de-campos', 'BlockController@index')->name('dashboard.block.index');
    Route::post('/bloque-de-campos/almacenar', 'BlockController@store')->name('dashboard.block.store');
    Route::post('/bloque-de-campos/listar-bloques', 'BlockController@list')->name('dashboard.block.list');
    Route::post('/bloque-de-campos/bloque', 'BlockController@getBlock')->name('dashboard.block.get_block');
    Route::post('/bloque-de-campos/eliminar', 'BlockController@delete')->name('dashboard.block.delete');
    Route::post('/bloque-de-campos/deshabilitar', 'BlockController@deshabilitar')->name('dashboard.block.deshabilitar');
    Route::post('/bloque-de-campos/habilitar', 'BlockController@habilitar')->name('dashboard.block.habilitar');

    //CONFIGURACION DE CAMPAÑAS - CAMPOS
    Route::get('/campos', 'FieldController@index')->name('dashboard.field.index');
    Route::post('/campos/almacenar', 'FieldController@store')->name('dashboard.field.store');
    Route::post('/campos/listar-campos', 'FieldController@list')->name('dashboard.field.list');
    Route::post('/campos/campo', 'FieldController@getField')->name('dashboard.field.get_field');
    Route::post('/campos/bloque', 'FieldController@getBlock')->name('dashboard.field.get_block');
    Route::post('/campos/tipo-de-campo', 'FieldController@getTypeField')->name('dashboard.field.get_type_field');
    Route::post('/campos/ancho', 'FieldController@getWidth')->name('dashboard.field.get_width');
    Route::post('/campos/grupo-de-usuario', 'FieldController@getUserGroup')->name('dashboard.field.get_user_group');
    Route::post('/campos/estados', 'FieldController@getState')->name('dashboard.field.get_state');
    Route::post('/campos/eliminar', 'FieldController@delete')->name('dashboard.field.delete');
    Route::post('/campos/deshabilitar', 'FieldController@deshabilitar')->name('dashboard.field.deshabilitar');
    Route::post('/campos/habilitar', 'FieldController@habilitar')->name('dashboard.field.habilitar');

    //VENTAS
    Route::get('/ventas', 'SoldController@index')->name('dashboard.sold.index');
    Route::get('/ventas/crear', 'SoldController@create')->name('dashboard.sold.create');
    Route::post('/ventas/files', 'SoldController@upload')->name('dashboard.sold.upload');
    Route::post('/ventas/guardar', 'SoldController@store')->name('dashboard.sold.store');
    Route::post('/ventas/listar', 'SoldController@list')->name('dashboard.sold.list');
    Route::post('/ventas/deshabilitar', 'SoldController@deshabilitar')->name('dashboard.sold.deshabilitar');
    Route::post('/ventas/habilitar', 'SoldController@habilitar')->name('dashboard.sold.habilitar');
    Route::post('/ventas/eliminar', 'SoldController@delete')->name('dashboard.sold.delete');
    Route::get('/ventas/download/{id}', 'SoldController@download')->name('dashboard.sold.download');

    //CAMPAÑAS
    Route::get('/campañas', 'CampainController@index')->name('dashboard.campain.index');
    Route::post('/campañas/campaña', 'CampainController@getCampain')->name('dashboard.campain.getCampain');
    Route::post('/campañas/eliminar', 'CampainController@delete')->name('dashboard.campain.delete');
    Route::post('/campañas/deshabilitar', 'CampainController@deshabilitar')->name('dashboard.campain.deshabilitar');
    Route::post('/campañas/habilitar', 'CampainController@habilitar')->name('dashboard.campain.habilitar');
    Route::post('/campañas/guardar', 'CampainController@store')->name('dashboard.campain.store');

    //ANUNCIOS
    Route::get('/anuncios', 'AdvertisementController@index')->name('dashboard.advertisement.index');
    Route::post('/anuncios/guardar', 'AdvertisementController@store')->name('dashboard.advertisement.store');
    Route::post('/anuncios/anucio', 'AdvertisementController@getAdvertisement')->name('dashboard.advertisement.getAdvertisement');
    Route::post('/anuncios/eliminar', 'AdvertisementController@delete')->name('dashboard.advertisement.delete');
    Route::post('/anuncios/deshabilitar', 'AdvertisementController@deshabilitar')->name('dashboard.advertisement.deshabilitar');
    Route::post('/anuncios/habilitar', 'AdvertisementController@habilitar')->name('dashboard.advertisement.habilitar');
});
