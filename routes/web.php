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

Route::get('/', function () {
    if (Auth::user()) {
        return redirect('dashboard');
    }

    return view('welcome');
});

Auth::routes();

Route::get('/dashboard', 'HomeController@index')->name('dashboard');
Route::get('/sensor/configure', 'HomeController@sensorConfigure')->name('sensor.configure');
Route::post('/sensor/update', 'HomeController@sensorUpdate')->name('sensor.update');
Route::get('/sensor/fetch', 'HomeController@sensorFetch')->name('sensor.fetch');

Route::post('/sensor/update/temperature', 'HomeController@updateTemperature')
    ->name('sensor.update.temperature');

Route::post('/sensor/update/humidity', 'HomeController@updateHumidity')
    ->name('sensor.update.humidity');
