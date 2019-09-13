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


//Forma de Pagamento
Route::get('/', 'PagamentoController@index');

// Rota para processar o pagamento
Route::post('/pagar', 'PagamentoController@pagarComPaypal')->name('pagar_com_paypal');


Route::get('/status', 'PagamentoController@s')->name('index');


Route::get('/', function () {
    return view('welcome');
});


