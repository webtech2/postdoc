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

Auth::routes([
  'register' => false, 
  'reset' => false, 
  'verify' => false,
]);

Route::get('/home', 'HomeController@index')->name('home');

Route::resource('source', 'SourceController');
Route::resource('dataset', 'DataSetController');
Route::get('sdataset/{sid}', 'DataSetController@sourceDSIndex');
Route::resource('property', 'PropertyController')->except('store');
Route::get('sproperty/{sid}/create', 'PropertyController@createForSource');
Route::get('dsproperty/{id}/create', 'PropertyController@createForDataSet');
Route::post('property/{object}/{id}', 'PropertyController@store');
Route::resource('change', 'ChangeController');
