<?php

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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/', 'Principal\PrincipalController@index')->name('Principal');

Route::get('/repo/update', array('as'=>'repositorio.bulkupdate', 'uses'=>'Repositorio\RepositorioController@bulkupdate'));
Route::get('/repo/download', array('as'=>'repositorio.bulkdownload', 'uses'=>'Repositorio\RepositorioController@bulkdownload'));
Route::get('/maketree', array('as'=>'maketree', 'uses'=>'Repositorio\RepositorioController@maketree'));
Route::get('/maketreehtml/{id}/{path?}', array('as'=>'principal.maketree', 'uses'=>'Principal\PrincipalController@maketree'))->where('path', '.*');