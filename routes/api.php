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
Route::get('getUsers', 'AdminController@index');
Route::post('editUser/{id}', 'AdminController@update')->name('edit');
Route::post('editSupport/{value}', 'SupportController@supportUpdate')->name('editSupport');
Route::post('deleteSupport/{value}', 'SupportController@supportDelete')->name('deleteSupport');
Route::post('createSupport', 'SupportController@supportCreate')->name('createSupport');
Route::post('updateStudentSupports', 'SupportController@updateStudentSupports')->name('updateStudentSupports');

//student
Route::get('getEnee', 'StudentController@index');
Route::get('getContacts/{id}', 'StudentController@getContacts');
Route::post('setMeeting/{id}', 'StudentController@setMeeting');
Route::post('setService/{id}', 'StudentController@setService');
Route::get('getContacts/{id}', 'StudentController@getContacts');
Route::get('getServices/{id}', 'StudentController@getServices');
Route::get('getMyMeetings/{email}', 'StudentController@myMeetings');
Route::post('subscription/{id}', 'StudentController@subscription');
Route::get('residence/{residence}/{area}', 'StudentController@getResidence');
Route::get('getUser/{id}', 'StudentController@show');

//service
Route::post('setContact/{id}', 'ServiceController@contact');
Route::get('getMeetings', 'ServiceController@meetings');
Route::post('finalizeMeeting/{id}', 'ServiceController@finalizeMeeting');
Route::get('getContact/{id}', 'ServiceController@contactDetails');
Route::post('changeNextContact/{id}', 'ServiceController@editContact');
Route::get('downloadHistory/{id}', 'ServiceController@downloadPDF');

//Director
Route::get('getSupports', 'SupportController@index');
Route::get('getStudentSupports/{email}', 'SupportController@byEmail');

//Case managers Responsible
Route::get('getCaseManagers', 'CaseManagerController@index');
Route::get('getCaseManagersForApproval', 'CaseManagerController@forApproval');
Route::post('setCM/{id}', 'CaseManagerController@setCM');
