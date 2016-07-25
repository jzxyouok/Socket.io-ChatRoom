<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/1', function () {
    return view('welcome');
});

Route::get('/first', function () {
   return response()->json(['name' => 'first', 'state' => 'first']);
});

Route::get('/second', function () {
   return response()->json(['name' => 'second', 'state' => 'second']);
});

Route::get('/socket', 'SocketController@index');
Route::post('/sendmessage', 'SocketController@sendMessage');
Route::post('/one2one', 'SocketController@one2one');
Route::post('/one2onejson', 'SocketController@one2onejson');
Route::get('/writemessage', 'SocketController@writemessage');
Route::post('/loginJson', 'SocketController@HandleloginJson');
Route::post('/logoutJson', 'SocketController@HandlelogoutJson');

/////////////////// Chat Allication Login API's ////////////////////
Route::get('/', 'chatApplicationController@AppLogin');
Route::post('/chatScreen', 'chatApplicationController@chatScreen');
Route::post('/informNodeIamLoggedIn','chatApplicationController@informNodeIamLoggedIn');
Route::post('/sendToAll', 'chatApplicationController@sendToAll');
Route::post('/logOutMe', 'chatApplicationController@logOutMe');

///////////////////////////