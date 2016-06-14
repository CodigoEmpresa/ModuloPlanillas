<?php
session_start();
/*
|--------------------------------------------------------------------------
| Routes File
|--------------------------------------------------------------------------
|
| Here is where you will register all of the routes in an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

use JasperPHP\JasperPHP as JasperPHP;

Route::get('/personas', '\Idrd\Usuarios\Controllers\PersonaController@index');
Route::get('/personas/service/obtener/{id}', '\Idrd\Usuarios\Controllers\PersonaController@obtener');
Route::get('/personas/service/buscar/{key}', '\Idrd\Usuarios\Controllers\PersonaController@buscar');
Route::get('/personas/service/ciudad/{id_pais}', '\Idrd\Usuarios\Controllers\LocalizacionController@buscarCiudades');
Route::post('/personas/service/procesar', '\Idrd\Usuarios\Controllers\PersonaController@procesar');

Route::any('/', 'Planilla\PlanillasController@index');
Route::any('/logout', 'Planilla\PlanillasController@logout');

Route::group(['middleware' => ['web']], function () {
	Route::get('/planillas', 'Planilla\PlanillasController@index');
	Route::get('/planillas/service/obtener/{Id_Planilla}', 'Planilla\PlanillasController@obtener');
	Route::get('/planillas/service/rubros/{Id_Fuente}', 'Planilla\PlanillasController@rubrosFuentes');
	Route::post('/planillas/service/procesar', 'Planilla\PlanillasController@procesar');
	Route::get('/planillas/{Id_Planilla}/recursos', 'Planilla\PlanillasController@recursos');
	Route::post('/planillas/recursos', 'Planilla\PlanillasController@sincronizarRecursos');

	Route::get('/contratistas', 'Planilla\ContratistasController@index');
	Route::get('/contratistas/service/obtener/{id}', 'Planilla\ContratistasController@obtener');
	Route::get('/contratistas/service/buscar/{key}', 'Planilla\ContratistasController@buscar');
	Route::post('/contratistas/service/procesar', 'Planilla\ContratistasController@procesar');
	Route::get('/contratistas/{Id_Contratista}/contratos', 'Planilla\ContratosController@obtenerPorContratista');

	Route::get('/contratos', 'Planilla\ContratosController@index');
	Route::get('/contratos/service/buscar/{key}', 'Planilla\ContratosController@buscar');
	Route::get('/contratos/{Id_Contratista}/crear', 'Planilla\ContratosController@crear');
	Route::get('/contratos/{Id_Contratista}/editar/{Id_Contrato}', 'Planilla\ContratosController@editar');
	Route::get('/contratos/{Id_Contrato}/eliminar', 'Planilla\ContratosController@eliminar');
	Route::post('/contratos', 'Planilla\ContratosController@guardar');
	Route::put('/contratos', 'Planilla\ContratosController@actualizar');

	Route::get('/bancos', 'Planilla\BancosController@index');
	Route::get('/bancos/service/obtener/{Id_Banco}', 'Planilla\BancosController@obtener');
	Route::get('/bancos/service/delete/{Id_Banco}', 'Planilla\BancosController@eliminar');
	Route::post('/bancos/service/procesar', 'Planilla\BancosController@procesar');

	Route::get('/fuentes', 'Planilla\FuentesController@index');
	Route::get('/fuentes/service/obtener/{Id_Banco}', 'Planilla\FuentesController@obtener');
	Route::get('/fuentes/service/delete/{Id_Banco}', 'Planilla\FuentesController@eliminar');
	Route::post('/fuentes/service/procesar', 'Planilla\FuentesController@procesar');

	Route::get('/rubros', 'Planilla\RubrosController@index');
	Route::get('/rubros/service/obtener/{Id_Rubro}', 'Planilla\RubrosController@obtener');
	Route::get('/rubros/service/delete/{Id_Rubro}', 'Planilla\RubrosController@eliminar');
	Route::post('/rubros/service/procesar', 'Planilla\RubrosController@procesar');

	Route::get('/componentes', 'Planilla\ComponentesController@index');
	Route::get('/componentes/service/obtener/{Id_Rubro}', 'Planilla\ComponentesController@obtener');
	Route::get('/componentes/service/delete/{Id_Rubro}', 'Planilla\ComponentesController@eliminar');
	Route::post('/componentes/service/procesar', 'Planilla\ComponentesController@procesar');
});