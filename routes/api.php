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
    Route::get('getAuthUser', 'Auth\LoginController@getAuthUser');
    //admin
    Route::get('getUsers', 'AdminController@index');
    Route::post('editUser/{id}', 'AdminController@update')->name('edit');
    Route::post('editSupport/{value}', 'SupportController@supportUpdate')->name('editSupport');
    Route::post('deleteSupport/{value}', 'SupportController@supportDelete')->name('deleteSupport');
    Route::post('createSupport', 'SupportController@supportCreate')->name('createSupport');

    //student
    Route::get('getEnee', 'StudentController@index');
    Route::get('getApprovedEnee', 'StudentController@enee');
    Route::get('getContacts', 'StudentController@getContacts');
    Route::post('setMeeting', 'StudentController@setMeeting');
    Route::post('setService', 'StudentController@setService');
    Route::get('getContacts', 'StudentController@getContacts');
    Route::get('getServices', 'StudentController@getServices');
    Route::get('getMyMeetings/{id}', 'StudentController@myMeetings');
    Route::post('subscription', 'StudentController@subscription');
    Route::get('residence/{residence}/{area}', 'StudentController@getResidence');
    Route::get('getUser/{id}', 'StudentController@show');
    Route::get('supportHours', 'StudentController@supportHours');
    Route::post('setSupportHours', 'StudentController@setSupportHours');
    Route::post('editProfile', 'StudentController@edit');
    Route::get('getNee/{id}', 'StudentController@getNee');

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
    Route::post('eneeAdd', 'ServiceController@create');


    //Director
    Route::get('getSupports', 'SupportController@index');
    Route::get('getStudentSupports/{email}', 'SupportController@byEmail');
    Route::post('reproveSubscription/{id}', 'SupportController@reproveSubscription');
    Route::post('servicesApprovalRequest/{id}', 'DirectorController@approvalRequest');
    Route::post('updateStudentSupports', 'SupportController@updateStudentSupports')->name('updateStudentSupports');
    Route::post('updateEnee', 'DirectorController@updateEnee');
    Route::get('medicalReport/download/{id}', 'DirectorController@downloadStudentDocuments');


    //Coordinator
    Route::get('getRequests', 'CoordinatorController@requests');
    Route::post('approveEneeStatus/{id}', 'CoordinatorController@approve');
    Route::post('denyEneeStatus/{id}', 'CoordinatorController@deny');

    //Case managers Responsible
    Route::get('getCaseManagers', 'CaseManagerResponsibleController@index');
    Route::get('getCaseManagersForApproval', 'CaseManagerResponsibleController@forApproval');
    Route::post('setCM/{id}', 'CaseManagerResponsibleController@setCM');
    Route::get('getStudents', 'CaseManagerResponsibleController@getStudents');
    Route::get('getStudentCMs/{email}', 'CaseManagerResponsibleController@getStudentCMs');
    Route::post('removeCM/{email}', 'CaseManagerResponsibleController@removeCM');

    //Case Manager
    Route::get('getCmEnee/{id}', 'CaseManagerController@getCmEnee');
    Route::get('getEneeInteractions/{email}', 'CaseManagerController@getEneeInteractions');
    Route::get('statistics/{stats}', 'CaseManagerController@statistics');
    Route::post('setInteraction', 'CaseManagerController@setInteraction');
    Route::get('contact/download/{id}', 'CaseManagerController@downloadContactFiles');
    Route::post('setEneeMeeting/{id}', 'CaseManagerController@setEneeMeeting');
    Route::get('getEneePlan/{id}', 'CaseManagerController@getEneePlan');
    Route::post('updatePlan/{id}', 'CaseManagerController@updatePlan');
    Route::post('setPlan', 'CaseManagerController@setPlan');
});
