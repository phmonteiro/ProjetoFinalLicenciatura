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

Route::middleware('auth:api')->group(function () {
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

    //services
    Route::post('setContact/{id}', 'ServiceController@contact');
    Route::get('getMeetings', 'ServiceController@meetings');
    Route::post('finalizeMeeting/{id}', 'ServiceController@finalizeMeeting');
    Route::get('getHistory/{id}', 'ServiceController@getHistory');
    Route::post('changeNextContact/{id}', 'ServiceController@editContact');
    Route::get('downloadHistory/{id}', 'ServiceController@downloadPDF');
    Route::get('getServicesRequests', 'ServiceController@getServicesRequests');
    Route::post('approveEneeStatusByServices/{id}', 'ServiceController@approve');
    Route::post('denyEneeStatusByServices/{id}', 'ServiceController@deny');
    Route::get('getEnees', 'ServiceController@index');

    //Director
    Route::get('getSupports', 'SupportController@index');
    Route::get('getStudentSupports/{email}', 'SupportController@byEmail');
    Route::post('reproveSubscription/{id}', 'SupportController@reproveSubscription');
    Route::post('servicesApprovalRequest/{id}', 'DirectorController@approvalRequest');

    //Coordinator
    Route::get('getRequests', 'CoordinatorController@requests');
    Route::post('approveEneeStatus/{id}', 'CoordinatorController@approve');
    Route::post('denyEneeStatus/{id}', 'CoordinatorController@deny');

    //Case managers Responsible
    Route::get('getCaseManagers', 'CaseManagerController@index');
    Route::get('getCaseManagersForApproval', 'CaseManagerController@forApproval');
    Route::post('setCM/{id}', 'CaseManagerController@setCM');
    Route::get('getStudents', 'CaseManagerController@getStudents');
    Route::get('getStudentCMs/{email}', 'CaseManagerController@getStudentCMs');
    Route::post('removeCM/{email}', 'CaseManagerController@removeCM');

});
