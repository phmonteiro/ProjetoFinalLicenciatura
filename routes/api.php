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
Route::get('getActiveEmail/{email}', 'Auth\LoginController@getActiveEmail');

//GetTeachers
Route::get('getTeachers', 'AdminController@getTeachers')->name('getTeachers');


Route::middleware('auth:api')->group(function () {
    Route::get('getAuthUser', 'Auth\LoginController@getAuthUser');
    //admin
    Route::middleware('isAdmin')->post('setResponsibleCM', 'AdminController@setResponsibleCM');
    Route::middleware('isAdmin')->post('setHoursLimit', 'AdminController@setHoursLimit');
    Route::middleware('isAdmin')->get('getHoursLimit', 'AdminController@getHoursLimit');
    Route::middleware('isAdmin')->get('getUsers', 'AdminController@index');
    Route::middleware('isAdmin')->patch('editUser/{id}', 'AdminController@update')->name('edit');
    Route::middleware('isAdmin')->patch('editSupportCategory/{id}', 'SupportController@supportCategoryUpdate')->name('editSupportCategory');
    Route::middleware('isAdmin')->delete('deleteSupportCategory/{id}', 'SupportController@supportCategoryDelete')->name('deleteSupportCategory');
    Route::middleware('isAdmin')->post('createSupportCategory', 'SupportController@supportCategoryCreate')->name('createSupportCategory');
    Route::middleware('isAdmin')->post('createSupport', 'SupportController@createSupport')->name('createSupport');
    Route::middleware('isAdmin')->patch('updateSupport/{id}', 'SupportController@updateSupport')->name('updateSupport');
    Route::middleware('isAdmin')->delete('deleteSupport/{id}', 'SupportController@deleteSupport')->name('deleteSupport');
    Route::middleware('isAdmin')->post('addCoordinator', 'AdminController@addCoordinator')->name('addCoordinator');

    //student
    Route::middleware('isStudent')->post('updateTransferredAccount', 'StudentController@updateTransferredAccount');
    Route::middleware('isStudent')->get('getSupportRequestsByStudent', 'StudentController@getSupportRequestsByStudent');
    Route::middleware('isStudentNoStatus')->post('getWebServiceUserInfo', 'StudentController@getWebServiceUserInfo');
    Route::middleware('isStudentOrCaseManager')->get('getAcademicRecord/{id}', 'StudentController@getAcademicRecord');
    Route::middleware('isStudentWithoutENEStatus')->post('transferAccountStatus', 'StudentController@transferAccountStatus');
    Route::middleware('isStudentWithoutENEStatus')->post('setEneeStatusExpired/{id}', 'StudentController@setEneeStatusExpired');
    Route::middleware('isStudentOrCaseManager')->get('getTotalSupportHours/{id}', 'StudentController@getTotalSupportHours');
    Route::middleware('isDirector')->get('getEnee', 'StudentController@index');
    Route::middleware('isDirector')->get('getApprovedEnee', 'StudentController@enee');
    Route::middleware('isStudent')->get('getContacts', 'StudentController@getContacts');
    Route::middleware('isStudent')->post('setMeeting', 'StudentController@setMeeting');
    Route::middleware('isStudent')->post('setService', 'StudentController@setService');
    Route::middleware('isStudent')->get('getContacts', 'StudentController@getContacts');
    Route::middleware('isStudent')->get('getSupportsByStudent', 'StudentController@getSupportsByStudent');
    Route::middleware('isStudent')->get('getStudentMeetings', 'StudentController@myMeetingsStudent');
    Route::middleware('isStudentNoStatus')->post('subscription', 'StudentController@subscription');
    Route::middleware('isAsStudentNoStatus')->get('residence/{residence}/{area}', 'StudentController@getResidence');
    Route::middleware('isService')->get('getUser/{id}', 'StudentController@show');
    Route::middleware('isStudentOrCaseManager')->get('supportHours/{id}', 'StudentController@supportHours');
    Route::middleware('isStudentOrCaseManager')->post('setSupportHours', 'StudentController@setSupportHours');
    Route::middleware('isStudent')->put('editProfile', 'StudentController@edit');
    Route::middleware('isDirectorServices')->get('getNee/{id}', 'StudentController@getNee');
    Route::middleware('isStudentOrDirector')->get('getTeachersStudent/{id}', 'StudentController@getTeacherStudent');
    Route::middleware('isDirector')->get('getStudentTutor/{id}', 'StudentController@getStudentTutor');

    //services
    Route::middleware('isService')->post('setContact/{id}', 'ServiceController@contact');
    Route::middleware('isService')->get('getMeetings', 'ServiceController@meetings');
    Route::middleware('isService')->post('finalizeMeeting/{id}', 'ServiceController@finalizeMeeting');
    Route::middleware('isService')->get('getHistory/{id}', 'ServiceController@getHistory');
    Route::middleware('isService')->patch('changeNextContact/{id}', 'ServiceController@editContact');
    Route::middleware('isService')->get('downloadHistory/{id}', 'ServiceController@downloadPDF');
    Route::middleware('isService')->get('getServicesRequests', 'ServiceController@getServicesRequests');
    Route::middleware('isService')->patch('approveEneeStatusByServices/{id}', 'ServiceController@approve');
    Route::middleware('isService')->patch('denyEneeStatusByServices/{id}', 'ServiceController@deny');
    Route::middleware('isService')->get('getEnees', 'ServiceController@index');
    Route::middleware('isDirector')->get('getServicesEvaluation/{id}', 'ServiceController@getServicesEvaluation');
    Route::middleware('isAcademicServices')->post('eneeAdd', 'ServiceController@create');

    //Director
    Route::middleware('isDirector')->get('getStudentTutor/{id}', 'StudentController@getStudentTutor');
    Route::middleware('isAdminDirectorStudentCaseManager')->get('getSupports', 'SupportController@getSupports');
    Route::middleware('isAdminDirectorStudentCaseManager')->get('getSupportsByCategory', 'SupportController@getSupportsByCategory');
    Route::middleware('isAdminDirectorStudentCaseManager')->get('getSupportCategories', 'SupportController@getSupportCategories');
    Route::middleware('isDirector')->patch('reproveSubscription/{id}', 'SupportController@reproveSubscription');
    Route::middleware('isDirector')->post('servicesApprovalRequest/{id}', 'DirectorController@approvalRequest');
    Route::middleware('isDirector')->put('updateStudentSupports', 'SupportController@updateStudentSupports')->name('updateStudentSupports');
    Route::middleware('isDirector')->put('updateEnee', 'DirectorController@updateEnee');
    Route::middleware('isDirectorServices')->get('medicalReport/download/{id}', 'DirectorController@downloadStudentDocuments');


    //Coordinator
    Route::middleware('isCoordinator')->get('getRequests', 'CoordinatorController@requests');
    Route::middleware('isCoordinator')->patch('approveEneeStatus/{id}', 'CoordinatorController@approve');
    Route::middleware('isCoordinator')->patch('denyEneeStatus/{id}', 'CoordinatorController@deny');

    //Case managers Responsible
    Route::middleware('isCaseManagerOrDirector')->get('getStudentSupports/{email}', 'SupportController@getSupportsByStudent');
    Route::middleware('isCaseManagerResponsible')->get('getEneWithoutCaseManager', 'CaseManagerResponsibleController@getEneWithoutCaseManager');
    Route::middleware('isCaseManagerResponsible')->get('substitutionsHistory', 'CaseManagerResponsibleController@substitutionsHistory');
    Route::middleware('isCaseManagerResponsible')->get('getActiveSubstitutions', 'CaseManagerResponsibleController@getActiveSubstitutions');
    Route::middleware('isCaseManagerResponsible')->put('cancelSubstitution', 'CaseManagerResponsibleController@cancelSubstitution');
    Route::middleware('isCaseManagerResponsible')->get('getAllCMs', 'CaseManagerResponsibleController@getAllCMs');
    Route::middleware('isCaseManagerResponsible')->post('addCM', 'CaseManagerResponsibleController@addCM');
    Route::middleware('isCaseManagerResponsible')->post('setCmSubstitute', 'CaseManagerResponsibleController@setCmSubstitute');
    Route::middleware('isCaseManagerResponsible')->get('getCaseManagers', 'CaseManagerResponsibleController@index');
    Route::middleware('isCaseManagerResponsible')->post('setCM/{id}', 'CaseManagerResponsibleController@setCM');
    Route::middleware('isCaseManagerResponsible')->get('getStudents', 'CaseManagerResponsibleController@getStudents');
    Route::middleware('isCaseManagerResponsible')->get('getStudentCMs/{email}', 'CaseManagerResponsibleController@getStudentCMs');
    Route::middleware('isCaseManagerResponsible')->delete('removeCM/{id}', 'CaseManagerResponsibleController@removeCM');

    //Case Manager
    Route::middleware('isCaseManager')->post('addStudentSupport/{id}', 'CaseManagerController@addStudentSupport');
    Route::middleware('isCaseManager')->patch('rejectStudentSupport/{id}', 'CaseManagerController@rejectStudentSupport');
    Route::middleware('isCaseManager')->get('getSupportRequests', 'CaseManagerController@supportRequests');
    Route::middleware('isCaseManager')->get('getENEHistories/{email}', 'CaseManagerController@getENEHistories');
    Route::middleware('isCaseManager')->get('checkSubstitution', 'CaseManagerController@checkSubstitution');
    Route::middleware('isCaseManager')->get('getEmailCaseManagerResponsible', 'CaseManagerController@getEmailCaseManagerResponsible');
    Route::middleware('isCaseManager')->put('setSupportHours/{id}', 'CaseManagerController@setSupportHours');
    Route::middleware('isCaseManager')->get('getCmEnee/{id}', 'CaseManagerController@getCmEnee');
    Route::middleware('isCaseManager')->get('getEneeInteractions/{email}', 'CaseManagerController@getEneeInteractions');
    Route::middleware('isCaseManager')->get('getMyMeetings', 'CaseManagerController@myMeetings');
    Route::middleware('isCaseManager')->get('statistics/{stats}', 'CaseManagerController@statistics');
    Route::middleware('isCaseManager')->post('setInteraction', 'CaseManagerController@setInteraction');
    Route::middleware('isCaseManager')->get('contact/download/{id}', 'CaseManagerController@downloadContactFiles');
    Route::middleware('isCaseManager')->put('setEneeMeeting/{id}', 'CaseManagerController@setEneeMeeting');
    Route::middleware('isCaseManager')->get('getEneePlan/{id}', 'CaseManagerController@getEneePlan');
    Route::middleware('isCaseManager')->put('updatePlan/{id}', 'CaseManagerController@updatePlan');
    Route::middleware('isCaseManager')->post('setPlan', 'CaseManagerController@setPlan');
    Route::middleware('isCaseManager')->post('addEvent', 'CaseManagerController@addEvent');
    Route::middleware('isCaseManager')->get('getEvents', 'CaseManagerController@getEvent');
});
