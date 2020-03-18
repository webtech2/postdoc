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

Route::get('/', 'HomeController@index')->name('home');

Auth::routes([
  'register' => false, 
  'reset' => false, 
  'verify' => false,
]);

Route::get('/home', 'HomeController@index')->name('home');

Route::resource('datasource', 'SourceController');
Route::resource('dataset', 'DataSetController')->except('create');;
Route::get('dataset/create/{object}/{id}', 'DataSetController@create');
Route::resource('dataitem', 'DataItemController');
Route::get('sdataset/{sid}', 'DataSetController@sourceDSIndex');
Route::resource('metadataproperty', 'PropertyController')->except('store');
Route::get('sproperty/{sid}/create', 'PropertyController@createForSource');
Route::get('dsproperty/{id}/create', 'PropertyController@createForDataSet');
Route::get('diproperty/{id}/create', 'PropertyController@createForDataItem');
Route::get('dhlproperty/{hlid}/create', 'PropertyController@createForDHlevel');
Route::post('property/{object}/{id}', 'PropertyController@store');
Route::resource('change', 'ChangeController');
Route::resource('datahighwaylevel', 'DataHighwayController');
Route::resource('mapping', 'MappingController');
