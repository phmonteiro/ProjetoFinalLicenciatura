<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
//login
Route::post('login', 'Auth\LoginController@login')->name('login');

//admin
Route::get('getContacts/{id}', 'StudentController@getContacts');
Route::get('getServices/{id}', 'StudentController@getServices');


Route::get('getUsers', 'AdminController@index');
Route::post('editUser/{id}', 'AdminController@update')->name('edit');

//student
Route::get('getEnee', 'StudentController@index');
Route::get('getContacts/{id}', 'StudentController@getContacts');
Route::post('setMeeting/{id}', 'StudentController@setMeeting');
Route::post('setService/{id}', 'StudentController@setService');
Route::get('getMyMeetings/{email}', 'StudentController@myMeetings');
Route::post('subscription/{id}', 'StudentController@subscription');
Route::get('zipCode/{zip}', 'StudentController@getZipCode');
Route::get('residence/{residence}', 'StudentController@getResidence');


//service
Route::post('setContact/{id}', 'ServiceController@contact');
Route::get('getMeetings', 'ServiceController@meetings');
Route::post('finalizeMeeting/{id}', 'ServiceController@finalizeMeeting');

Route::get('getUser/{id}', 'StudentController@getUser');
Route::get('getContact/{id}', 'ServiceController@contactDetails');

Route::post('changeNextContact/{id}', 'ServiceController@editContact');

Route::get('downloadHistory/{id}', 'ServiceController@downloadPDF');
