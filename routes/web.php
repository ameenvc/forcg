<?php

use App\Http\Controllers\Auth\AuthController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomFieldController;

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

// Example Routes
// Route::get('/', 'Auth\LoginController@showLoginForm');
Route::post('/ajaxAllUsers', 'UserController@ajaxShowAllUsers')->name('ajaxAllUsers');
// Route::get('/', 'UserController@checkLogin')->name('getlogin');
Route::get('/', function () {
    return redirect('/user/home');
})->name('getlogin');

Route::match(['get', 'post'], '/user/login', 'Auth\LoginController@login')->name('auth.login');
// Route::view('/login','auth.login');
ROute::view('user/register', 'auth.register');
Route::match(['get', 'post'], '/dashboard', function () {
    return view('dashboard');
});

//2fa setup
Route::get('/2fa/sendOtpToMail', 'Auth\TwoFactorController@sendOtpToMail')->name('2fa.sendOtpToMail');
Route::post('/2fa/verifyMailOtp', 'Auth\TwoFactorController@verifyMailOtp')->name('2fa.verifyMailOtp');
Route::get('/2fa/setup', 'Auth\TwoFactorController@setup2fa')->name('2fa.setup');
Route::post('/2fa/setup', 'Auth\TwoFactorController@setupComplete')->name('2fa.setup.post');
Route::post('/2fa/login', 'Auth\TwoFactorController@login')->name('2fa.login.post');
Route::post('/2fa/userEnableDisable2FA', 'Auth\TwoFactorController@userEnableDisable2FA')->name('2fa.userEnableDisable2FA.post');

Route::get('/2fa/setupPage', 'Auth\TwoFactorController@setup2faPage')->name('2fa.setupPage');
Route::post('/2fa/verify', function (Request $request)  {
    $redirectUrl = session()->pull('2fa_redirect_url', '/user/home');
    return redirect($redirectUrl);
})->name('2fa.verify')->middleware(['auth:web,admin', '2fa']);

//register new user
// Check if employee ID is unique
Route::get('/check-employee-id', 'Auth\RegisterController@checkEmployeeId');
Route::post('/user/register', 'Auth\RegisterController@create')->name('user.register');
Route::get('/user/send-registration-email','Auth\RegisterController@sendRegistrationEmail')->name('send.registration.email');
///route for blocking sighUp
Route::get('user/registration/hold', function () {
    return view('auth/regblock');
})->name('blockregister');

Route::view('/pages/slick', 'pages.slick');
Route::view('/pages/datatables', 'pages.datatables');
Route::view('/pages/blank', 'pages.blank');

Route::get('/admin/enable-disable-module/{ids}', 'AdminController@showEnableModulePage')->name('admin.enable_disable_module');
Route::post('/admin/enable-disable-module','AdminController@submitEnableModulePage')->name('admin.submit_enable_disable_module');

//success login of user/admin/superadmin
Route::get('/admin/home', 'AdminController@index')->name('admin.successlogin');
Route::get('/user/dashboard', 'UserController@userdashboardindex')->name('user.successlogin');
Route::get('/user/home', 'UserController@index')->name('user');
Route::get('/user/score', 'UserController@scoring')->name('user.score');
Route::get('/user/score/evidence', 'EsgEvidenceController@index');
Route::post('/user/score/evidence', 'EsgEvidenceController@store');
Route::get('/user/score/evidence/template', 'EsgEvidenceController@template');
Route::post('/user/score/evidence/import', 'EsgEvidenceController@import');
Route::post('/user/score/evidence/{id}/review', 'EsgEvidenceController@review');
Route::post('/user/score/evidence/{id}/withdraw', 'EsgEvidenceController@withdraw');
Route::get('/user/score/evidence/{id}/download', 'EsgEvidenceController@download');

Route::get('/superadmin/home', 'SuperAdminController@index')->name('superadmin.successlogin')->middleware('auth:superadmin');

//logout for each type of user
Route::get('user/logout', 'UserController@logout')->name('user.logout');
Route::get('admin/logout', 'AdminController@logout')->name('admin.logout');
Route::get('superadmin/logout', 'SuperAdminController@logout')->name('superadmin.logout');
Route::get('profile/edit', 'ProfileController@edit')->name('profile.edit');
Route::post('profile/update', 'ProfileController@update')->name('profile.update');
Route::get('profile/remove-avatar', 'ProfileController@removeAvatar')->name('profile.removeAvatar');

//Profile pic updation
Route::get('user/profile', 'UserController@profile')->name('user.profile');
Route::post('/user/updateProfile', 'UserController@updateProfile');
Route::get('user/profile/remove-avatar/{id}', 'UserController@remove_avatar')->name('user.remove.avatar');
Route::get('admin/update_profile', 'AdminController@profile')->name('admin.profile');
Route::post('/admin/updateProfile', 'AdminController@updateProfile');
Route::get('admin/profile/remove-avatar/{id}', 'AdminController@remove_avatar')->name('admin.remove.avatar');
//My Board
Route::get('user/my-board',[App\Http\Controllers\UserController::class,'myBoard'])->name('user.my-board');

//for admin to login as user using user privilege button
Route::get('/admin/user', function () {
    $models = DB::table('modenadisable')->First();
    return view('user.userhome')->with('model', $models);
})->name('adminuser')->middleware('auth:admin');

//Auth::routes(['verify' => true]);
Auth::routes();

Route::get('dashboard', [AuthController::class, 'dashboard'])->middleware(['auth', 'is_verify_email']);
Route::get('account/verify/{token}', 'Auth\AuthController@verifyAccount')->name('user.verify');
Route::get('check-session', 'Auth\AuthController@checkSession');

Route::get('/home', 'HomeController@index')->name('home');

//Super admin Routes
Route::get('/superadmin/module/enabledisable', 'SuperAdminController@enadismoduleview')->name('superadmin.edmodules')->middleware('auth:superadmin');
Route::get('/superadmin/custom-fields', 'SuperAdminController@showCustomFieldPage')->name('superadmin.custom_fields');
Route::post('/superadmin/custom-fields-update', 'SuperAdminController@updateCustomFieldEnadisable')->name('superadmin.custom_fields_enadisable');
Route::post('/superadmin/module/enadisconfirm', 'SuperAdminController@enadisconfirm')->name('superadmin.disenaconfirm');
Route::get('/superadmin/user/logs', 'SuperAdminController@viewlogs')->name('superadmin.logs');
Route::post('/superadmin/searchlogs', 'SuperAdminController@searchlogs')->name('superadmin.searchlogs');
Route::post('/superadmin/searchactivity', 'SuperAdminController@searchactivity')->name('superadmin.searchactivity');
Route::get('/superadmin/manage/companysize', 'SuperAdminController@compsize')->name('superadmin.compsize');
Route::post('/superadmin/update/companysize', 'SuperAdminController@updatecompsize')->name('superadmin.upcomsize');
Route::get('/superadmin/control/devicelogins', 'SuperAdminController@DevicelogsView')->name('superadmin.contlogins');
Route::post('/superadmin/control/loginsupdate', 'SuperAdminController@DevicelogsUpdate')->name('superadmin.updatelogins');
Route::get('superadmin/viewactivity', 'SuperAdminController@viewactivity')->name('superadmin.activity');
Route::get('superadmin/moduleoverview', 'SuperAdminController@moduleoverview')->name('superadmin.modoverview');
Route::post('/superadmin/moduleoverviewfilter', 'SuperAdminController@moduleoverviewfilter')->name('superadmin.modfilter');
Route::get('superadmin/error/logs', 'SuperAdminController@errorLogs')->name('superadmin.errorLogs');

Route::post('superadmin/delete/logs', 'SuperAdminController@deletelogs')->name('superadmin.dellogs');
Route::get('superadmin/data/backup', 'BackupController@databackup')->name('superadmin.databackup');
Route::get('/admin/data/backup', 'BackupController@databackup')->name('admin.databackup');
Route::post('backup/create', 'BackupController@create');
Route::get('backup/download/{file_name}', 'BackupController@download');
Route::get('backup/delete/{file_name}', 'BackupController@delete');
Route::post('backup/restore/DataBase', 'BackupController@restoredb')->name('backup/restore');

Route::post('/restorefiles', 'BackupController@restorefile')->name('backup/restorefile');
//superadmin route for email settings
Route::get('/superadmin/email/settings', 'SuperAdminController@getOldEmailSetting')->name('superadmin.getoldemailsetting');
Route::post('/superadmin/update/email/settings', 'SuperAdminController@updateNewEmailSetting')->name('updateemailsettings');

//route for lisence activation
Route::post('superadmin/code/generate', 'SuperAdminController@generate')->name('generate');
Route::post('superadmin/code/complete', 'SuperAdminController@activationComplete')->name('activationComplete');

//master reset and password reset routes of superadmin
Route::get('superadmin/masterreset/confirm', 'SuperAdminController@confirmMasterRest')->name('master.resetConfirm');
Route::get('superadmin/masterreset/backupfiles', 'SuperAdminController@fileBackup')->name('master.fileBackup');
Route::post('superadmin/masterreset/Confirmation', 'SuperAdminController@MasterReset')->name('master.masterreset');
Route::post('superadmin/masterreset/getcode', 'SuperAdminController@getCode')->name('getcode');
Route::get('superadmin/masterreset/initreset', 'SuperAdminController@initReset')->name('superadmin.initreset');
Route::get('/confirmation', 'Auth\ResetPasswordController@SuperadminResetConfrmCancel')->name('confirmed');
//view userlist page in user dashboard in user login
Route::get('/user/viewuserlist', 'UserController@viewusers');
//admin routes
//admin change password
Route::get('profile/password_and_security', 'ProfileController@passwordAndSecurity')->name('profile.passwordAndSecurity');
Route::post('profile/change_password', 'ProfileController@changePassword')->name('profile.changePassword');
Route::get('admin/change/password', 'AdminController@changePasswordpage');
Route::post('admin/change/password/confirm', 'AdminController@changePassword')->name('admin.changepassword');

//Change Password
Route::get('user/change/password', 'UserController@changePasswordpage');

//Change Password for Admin
// Route::get('admin/change/password',function(){
// return view('auth/passwords/changepassadmin');
// });
Route::post('user/change/password/confirm', 'UserController@changePassword')->name('user.changepassword');

//User registration approvals
Route::get('user/approval', 'AdminController@approveReg')->name('app.view');
Route::post('user/approval/accept', 'AdminController@approveaccept')->name('accept.approval');
Route::post('user/approval/reject', 'AdminController@approvereject')->name('reject.approval');
Route::get('/admin/viewUsers', 'AdminController@viewUsers')->name('admin.viewUsers');
Route::post('/admin/restoreUser/{id}', 'AdminController@restoreUser')->name('admin.restoreUser');
Route::post('/admin/restoreMultipleUsers', 'AdminController@restoreMultipleUsers')->name('admin.restoreMultipleUsers');
Route::post('/admin/permanentDeleteUser/{id}', 'AdminController@permanentDeleteUser')->name('admin.permanentDeleteUser');
Route::get('/admin/showResponsibilities/{id}', 'AdminController@showResponsibilities')->name('admin.showResponsibilities');
Route::post('/admin/postResponsibilities', 'AdminController@postResponsibilities')->name('admin.postResponsibilities');
Route::get('/admin/AddDetails', 'AdminController@AddDetails');

Route::post('/admin/updateDetails', 'AdminController@updateDetails');

Route::get('/admin/displayeditUsers', 'AdminController@displayeditUsers');
Route::post('/admin/updateUser', 'AdminController@updateUser');

Route::get('/admin/CarryUserID', 'AdminController@CarryUserID');
Route::post('/admin/ajaxDept', 'AdminController@ajaxDept')->name('admin.ajaxDept');
Route::post('/admin/ajaxAuditor', 'AdminController@ajaxAuditor')->name('admin.ajaxAuditor');

Route::get('/admin/removeUser', 'AdminController@removeUser');
Route::get('/admin/manageRoles', 'AdminController@manageRoles');
Route::post('/admin/manageroles', 'AdminController@updateUserRoles');

Route::get('/admin/user-delete/{id}', 'AdminController@userdelete')->name('admin.user.delete');

//--------------------- Department ------------------------------//
Route::get('/admin/viewDepartments', 'AdminController@viewDepartments');
Route::post('/admin/displaydepartmentcodeajax', 'AdminController@displaydepartmentcodeajax')->name('admin.displaydepartmentcodeajax');
Route::post('/admin/savedepartmentsajax', 'AdminController@savedepartmentsajax')->name('admin.savedepartmentsajax');
//--------------Department Divisions,Subdivions,Locations in department page starts ------------------------------//
//---------------------Divisions listing in department page------------------------------//
Route::post('/admin/department/selectdivisiondetails', 'AdminController@selectdivisiondetails')->name('admin.selectdivisiondetails');
Route::post('/admin/department/selectsubdivisiondetails', 'AdminController@selectsubdivisiondetails')->name('admin.selectsubdivisiondetails');
Route::post('/admin/department/geteditdivisiondetails', 'AdminController@geteditdivisiondetails')->name('admin.geteditdivisiondetails');
Route::post('/admin/department/updatedeptdivisions', 'AdminController@updatedeptdivisionajax')->name('admin.updatedeptdivisionajax');
Route::get('/admin/department/deletedeptdivisions/{id}', 'AdminController@deletedeptdivisions')->name('admin.deletedeptdivisions');
//---------------------Subdivisions listing in department page------------------------------//
Route::post('/admin/department/insertdeptsubdivisions', 'AdminController@insertdeptsubdivisionajax')->name('admin.insertdeptsubdivisionajax');
Route::post('/admin/department/geteditsubdivisiondetails', 'AdminController@geteditsubdivisiondetails')->name('admin.geteditsubdivisiondetails');
Route::post('/admin/department/updatedeptsubdivisions', 'AdminController@updatedeptsubdivisionajax')->name('admin.updatedeptsubdivisionajax');
Route::get('/admin/department/deletedeptsubdivisions/{id}', 'AdminController@deletedeptsubdivisions')->name('admin.deletedeptsubdivisions');
//---------------------Locations listing in department page------------------------------//
Route::post('/admin/department/selectlocationdetails', 'AdminController@selectlocationdetails')->name('admin.selectlocationdetails');
Route::post('/admin/department/insertdeptlocations', 'AdminController@insertdeptlocationajax')->name('admin.insertdeptlocationajax');
Route::post('/admin/department/geteditlocationdetails', 'AdminController@geteditlocationdetails')->name('admin.geteditlocationdetails');
Route::post('/admin/department/updatedeptlocations', 'AdminController@updatedeptlocationajax')->name('admin.updatedeptlocationajax');
Route::get('/admin/department/deletedeptlocations/{id}', 'AdminController@deletedeptlocations')->name('admin.deletedeptlocations');

//-------------- Department Divisions,Subdivions,Locations in department page ends ------------------------------//

Route::get('/admin/departmentAreas', 'AdminController@departmentAreas')->name('admin.departmentAreas');
//--------------------- Department Divisions ------------------------------//
Route::get('/admin/departmentDivisions', 'AdminController@departmentDivisions')->name('admin.departmentDivisions');
Route::post('/admin/addDepartmentDivisions', 'AdminController@addDepartmentDivisions')->name('admin.departmentDivisions.add');
Route::post('/admin/ajaxDepartmentDivisions/{id}', 'AdminController@ajaxDepartmentDivisions')->name('admin.ajaxDepartmentDivisions');
Route::post('/admin/departmentDivisions/edit/{id}', 'AdminController@editDepartmentDivisions')->name('admin.departmentDivisions.edit');
Route::get('/admin/departmentDivisions/delete/{id}', 'AdminController@deleteDivision')->name('admin.departmentDivisions.delete');
Route::post('/admin/departmentDivisions/addArea', 'AdminController@addAreasToADivision')->name('admin.departmentDivisions.addArea');
Route::get('/admin/departmentDivisions/getDivisionsOfDepartment/{id}', 'AdminController@getDivisionsOfADepartment')->name('admin.departmentDivisions.getDivisions');
Route::get('/admin/departmentDivisions/getAreasOfDivision/{id}', 'AdminController@getAreasOfADivision')->name('admin.departmentDivisions.getAreasOfADivision');
//--------------------- End: Department Divisions ------------------------------//
//---------------------- Area --------------------------------------------------//
Route::post('/admin/areas/edit/{id}', 'AdminController@EditArea')->name('admin.areas.edit');
Route::get('/admin/areas/delete/{id}', 'AdminController@DeleteArea')->name('admin.areas.delete');
Route::get('/admin/areas/getAreasOfDepartment/{id}', 'AdminController@getAreasOfADepartment')->name('admin.departmentAreas.getAreas');
//---------------------- End: Area --------------------------------------------------//

//---------------------- Standard -------------------------------------------------------//
Route::get('/admin/standards', 'AdminController@indexStandards')->name('admin.standards');
// Route::get('/admin/standards/ajaxLocationDepartments/{id}', 'AdminController@ajaxLocationDepartments')->name('admin.standards.ajaxLocationDepartments');
// Route::get('/admin/standards/ajaxLocationDivisions/{id}', 'AdminController@ajaxLocationDivisions')->name('admin.standards.ajaxLocationDivisions');
// Route::get('/admin/standards/ajaxLocationAreas/{id}', 'AdminController@ajaxLocationAreas')->name('admin.standards.ajaxLocationAreas');
// Route::post('/admin/standards/add', 'AdminController@storeLocation')->name('admin.standards.add');
// Route::get('/admin/standards/delete/{id}', 'AdminController@deleteLocation')->name('admin.standards.delete');
// Route::post('/admin/standards/edit/{id}', 'AdminController@editLocation')->name('admin.standards.edit');
//---------------------- End: Standard --------------------------------------------------//

//---------------------- Location -------------------------------------------------------//
Route::get('/admin/locations', 'AdminController@indexLocations')->name('admin.locations');
Route::get('/admin/locations/ajaxLocationDepartments/{id}', 'AdminController@ajaxLocationDepartments')->name('admin.locations.ajaxLocationDepartments');
Route::get('/admin/locations/ajaxLocationDivisions/{id}', 'AdminController@ajaxLocationDivisions')->name('admin.locations.ajaxLocationDivisions');
Route::get('/admin/locations/ajaxLocationAreas/{id}', 'AdminController@ajaxLocationAreas')->name('admin.locations.ajaxLocationAreas');
Route::post('/admin/locations/add', 'AdminController@storeLocation')->name('admin.locations.add');
Route::get('/admin/locations/delete/{id}', 'AdminController@deleteLocation')->name('admin.locations.delete');
Route::post('/admin/locations/edit/{id}', 'AdminController@editLocation')->name('admin.locations.edit');
//---------------------- End: Location --------------------------------------------------//
Route::post('/admin/ajaxDepartmentAreas/{id}', 'AdminController@ajaxDepartmentAreas')->name('admin.ajaxDepartmentAreas');
Route::get('/admin/editDepartment', 'AdminController@editDepartment');
Route::post('/admin/updateDepartment', 'AdminController@updateDepartment');
Route::get('/admin/CarryDepartmentID', 'AdminController@CarryDepartmentID');
Route::get('/admin/removeDepartment', 'AdminController@removeDepartment');
Route::get('/admin/searchedDepartment', 'AdminController@searchedDepartment');
Route::post('/admin/searchedDepartment', 'AdminController@searchedDepartment');
//Route::post('/admin/addDepartment', 'AdminController@addDepartment');
Route::get('/admin/addhodassistants', 'AdminController@addhodassist');
Route::post('/admin/inserthodassts', 'AdminController@inserthodasst');

//---------------------- Enable disable departments -------------------------------------------------------//
Route::post('/admin/departments/enable-disable/{id}', 'AdminController@enabledisabledepartment')->name('admin.dept.enable.disable');
//---------------------- End: Enable disable departments --------------------------------------------------//
//Route::get('/testm', 'AdminController@testm');
//calibration

//secondary admin
Route::get('/admin/viewAdmins', 'AdminController@viewAdmins');
Route::get('/admin/editDepartment', 'AdminController@editDepartment');
Route::post('/admin/updateDepartment', 'AdminController@updateDepartment');
Route::get('/admin/CarryAdminID', 'AdminController@CarryAdminID');
Route::get('/admin/removeAdmin', 'AdminController@removeAdmin');
Route::get('/admin/searchedDepartment', 'AdminController@searchedDepartment');
Route::post('/admin/searchedDepartment', 'AdminController@searchedDepartment');
Route::post('/admin/addAdmin', 'AdminController@addAdmin');
//doctype

//admin doc types
Route::get('/admin/doctypes', 'AdminController@doctypes');
Route::post('/admin/doctypes-code', 'AdminController@generateDoctypeCode')->name('admin.doctype.code');
Route::post('/admin/doctypes-code-validation', 'AdminController@validateDoctypeCode')->name('admin.doctype.validation.code');
Route::get('/admin/{id}/editdoctype', 'AdminController@editdoctype')->name('admin.editdoctype');
Route::post('/admin/{id}/updatedoctype', 'AdminController@updatedoctype')->name('admin.updatedoctype');
Route::get('/admin/removedoctype', 'AdminController@removedoctype');
Route::get('/admin/searcheddoctype', 'AdminController@searcheddoctype');
Route::post('/admin/searcheddoctype', 'AdminController@searcheddoctype');
Route::post('/admin/adddoctype', 'AdminController@adddoctype')->name('admin.adddoctype');
Route::post('/admin/reorderdoctypes', 'AdminController@reorderDoctypes')->name('admin.reorderdoctypes');

//calibration

Route::get('/admin/viewfreq', 'AdminController@viewfreq');
Route::post('/admin/addfreq', 'AdminController@addfreq');
Route::get('/admin/removefreq', 'AdminController@removefreq');
Route::get('/admin/calibrationlist', 'AdminController@calibrationdetails');
Route::get('/admin/addcalibration', 'AdminController@addcalibrationlist');
Route::post('/admin/upload/caldetails', 'AdminController@insertcal');
Route::post('/admin/multiplecalaction', 'AdminController@multiplecalaction');
Route::post('/admin/calinstupdate', 'AdminController@updateallcal')->name('updatecalmastinst');
Route::get('/admin/calibration/calibration-field-configuration', 'Admin\Calibration\CalibrationFieldConfigurationController@index')->name('admin.calibration.calibrationFieldConfiguration');
Route::post('/admin/calibration/calibration-field-configuration', 'Admin\Calibration\CalibrationFieldConfigurationController@store')->name('admin.calibration.saveCalibrationFieldConfiguration');

//corrective type

Route::get('/admin/editTypeCorAction', 'AdminController@editTypeCorAction');
Route::post('/admin/updateType', 'AdminController@updateType');
Route::post('/admin/{typeId}/manage-workflow-modification', 'AdminController@updateManageWorkflowFlag')->name('admin.update.manage.workflow');

Route::post('/admin/updateTypeCorAction', 'AdminController@updateTypeCorAction');
Route::get('/admin/removeType', 'AdminController@removeType');
Route::get('/admin/removeTypeCorAction', 'AdminController@removeTypeCorAction');
Route::get('/admin/searchedType', 'AdminController@searchedType');
Route::post('/admin/searchedType', 'AdminController@searchedType');
Route::post('/admin/addType', 'AdminController@addType')->name('admin.addType');

Route::get('/admin/usermanage', 'AdminController@usermanage');

//audit type
Route::get('/admin/viewTypes', 'AdminController@viewTypes');
Route::get('/admin/editType', 'AdminController@editType');
Route::get('/admin/editisoclauses', 'AdminController@editisoclauses');
Route::post('/admin/updateisoclauses', 'AdminController@updateisoclauses');
Route::post('/admin/addstandard', 'AdminController@addstandard');

//category
Route::get('/admin/viewCategories', 'AdminController@viewCategories');
Route::get('/admin/editCategory', 'AdminController@editCategory');
Route::get('/admin/corrective/viewhistory/{fieldname}/{category}','AdminController@viewPNCHistory');
Route::get('/admin/corrective/view-ncr-added-field/{category}','AdminController@viewNcrAddedField');
Route::post('/admin/updateCategory', 'AdminController@updateCategory');
Route::get('/admin/removeCategory', 'AdminController@removeCategory');
Route::post('/admin/add-category', 'AdminController@addCategory')->name('admin.addCategory');
Route::get('/admin/check-category', 'AdminController@checkCategory')->name('admin.checkCategory');
Route::post('/admin/ajaxEnableSource','AdminController@ajaxEnableSource')->name('admin.corrective.ajax_enable_source');
//Corrective Detection Stage
Route::get('/admin/check-add-detection-stage', 'AdminController@checkAddDetectionStage');
Route::post('/admin/add-detection-stage', 'AdminController@addDetectionStage');
Route::get('/admin/remove-detection-stage/{id}', 'AdminController@removeDetectionStage');
Route::post('/admin/update-detection-stage/{id}', 'AdminController@updateDetectionStage');
Route::get('/admin/check-edit-detection-stage', 'AdminController@checkEditDetectionStage');
Route::post('/admin/enable-detection-stage', 'AdminController@enableDetectionStage');
Route::post('/admin/detection-stage/update-label', 'AdminController@updateDetectionStageLabel');

//audit checklist conformance
Route::get('/admin/addconformance', 'AdminController@addconformance')->name('addauditchecklistconformance');
Route::post('/admin/insertconformance', 'AdminController@insertconformance')->name('auditinsertconformance');
Route::get('/admin/editconformance/{id}', 'AdminController@editconformance')->name('editauditconformance');
Route::post('/admin/updateconformance/', 'AdminController@updateconformance')->name('updateauditconformance');

//audit root cause and corrective actions
Route::get('/admin/addrootcorrective', 'AdminController@addrootcorrective')->name('addauditaddrootcorrective');
Route::post('/admin/insertrootcorrective', 'AdminController@insertrootcorrective')->name('auditinsertrootcorrective');
Route::get('/admin/editrootcorrective/{id}', 'AdminController@editrootcorrective')->name('editauditrootcorrective');
Route::post('/admin/updaterootcorrective/', 'AdminController@updaterootcorrective')->name('updateauditrootcorrective');
Route::get('/admin/deleterootcorrective/{id}', 'AdminController@deleterootcorrective')->name('deleteauditrootcorrective');

//hse
Route::get('/admin/viewhseTypes', 'AdminController@viewhseTypes');
Route::get('/admin/edithseType', 'AdminController@edithseType');
Route::post('/admin/addhseType', 'AdminController@addhseType');
Route::post('/admin/updatehseType', 'AdminController@updatehseType');

Route::get('/admin/removehseType', 'AdminController@removehseType');
//category

Route::get('/admin/viewhseCategories', 'AdminController@viewhseCategories');
Route::get('/admin/edithseCategory', 'AdminController@edithseCategory');
Route::post('/admin/updatehseCategory', 'AdminController@updatehseCategory');
Route::get('/admin/removehseCategory', 'AdminController@removehseCategory');
Route::post('/admin/addhseCategory', 'AdminController@addhseCategory');

//for risk
Route::get('/admin/riskinfo', 'AdminController@riskinfo');
Route::post('/admin/addriskinfo', 'AdminController@addriskinfo');
Route::get('/admin/removeriskinfo', 'AdminController@removeriskinfo');
//for ohs
Route::get('/admin/ohsinfo', 'AdminController@ohsinfo');
Route::post('/admin/addohsinfo', 'AdminController@addohsinfo');
Route::get('/admin/removeohsinfo', 'AdminController@removeohsinfo');
//for env
Route::get('/admin/enviinfo', 'AdminController@enviinfo');
Route::post('/admin/addenvinfo', 'AdminController@addenvinfo');
Route::get('/admin/removeenvinfo', 'AdminController@removeenvinfo');
Route::get('/admin/nearmiss/qr','AdminController@nearMissQr')->name('nearmiss.qr');
Route::get('admin/nearmiss-qr/download/pdf', 'AdminController@downloadPDF')
    ->name('admin.qr.download.pdf');
//for enviaspect probabilities
Route::get('/admin/remove-env-probabilities/{id}', 'AdminController@removeEnvProbabilities')->name('probabilites.remove');
Route::post('/admin/edit-env-probabilities', 'AdminController@editEnvProbabilities')->name('probabilites.edit');

//for enviaspect consequences
Route::get('/admin/remove-env-consequences/{id}', 'AdminController@removeEnvConsequences')->name('consequences.remove');

//for enviaspect regulatory requirements
Route::get('/admin/remove-env-regulatory-requirements/{id}', 'AdminController@removeEnvRegulatory')->name('regulatory.remove');

//for enviaspect quantities
Route::get('/admin/remove-env-quantity/{id}', 'AdminController@removeEnvQunatity')->name('quantity.remove');

//for enviaspect control measures
Route::get('/admin/remove-env-control-measures/{id}', 'AdminController@removeEnvControlMeasures')->name('control_measures.remove');

//for add category in enviaspect
Route::post('/admin/addCategory', 'AdminController@addEnvCategory')->name('admin.enviaspect.add_category');

//for HR mgmt
Route::get('/admin/addjobcode', 'AdminController@addjobcode');
Route::post('/admin/submitjobcode', 'AdminController@submitjobcode');
//near miss types
Route::get('/admin/viewnearmissTypes', 'AdminController@viewnearmissTypes');
Route::post('/admin/addnearmissType', 'AdminController@addnearmissType');
Route::get('/admin/editnearmissType', 'AdminController@editnearmissType');
Route::post('/admin/updatenearmissType', 'AdminController@updatenearmissType');
Route::get('/admin/removenearmissType', 'AdminController@removenearmissType');
//nearmiss target info
Route::post('/admin/addnearmisstarget', 'AdminController@addnearmisstarget');
Route::get('/admin/editnearmisstarget', 'AdminController@editnearmisstarget');
Route::post('/admin/updatenearmisstarget', 'AdminController@updatenearmisstarget');
Route::get('/admin/removenearmisstarget', 'AdminController@removenearmisstarget');

//opportunity types
Route::get('/admin/viewopportunitytypes', 'AdminController@viewopportunitytypes');
Route::post('/admin/opportunity/addtypes', 'AdminController@addopportunitytypes');
Route::get('/admin/opportunity/edittype/{id}', 'AdminController@editopportunitytypes');
Route::post('/admin/opportunity/updateopportunitytype', 'AdminController@updateopportunitytype');
Route::get('/admin/opportunity/deletetype/{id}', 'AdminController@deleteopportunitytype');

//objective types
Route::get('/admin/viewobjectivetypes', 'AdminController@viewobjectivetypes');
Route::post('/admin/objective/addtypes', 'AdminController@addobjectivetypes');
Route::get('/admin/objective/edittype/{id}', 'AdminController@editobjectivetypes');
Route::post('/admin/objective/updateobjectivetype', 'AdminController@updateobjectivetype');
Route::get('/admin/objective/deletetype/{id}', 'AdminController@deleteobjectivetype');

//for the plants
Route::get('/admin/viewPlants', 'AdminController@viewPlants');
Route::post('/admin/addPlant','AdminController@addPlant');
Route::post('/admin/updatePlant','AdminController@updatePlant');
Route::post('/admin/deletePlant','AdminController@deletePlant');
//CONTEXT MODULE RELATED
Route::get('/admin/viewPurpose', 'AdminController@viewPurpose');
Route::post('/admin/addPurpose', 'AdminController@addPurpose');
Route::get('/admin/editPurpose', 'AdminController@editPurpose');
Route::post('/admin/updatePurpose', 'AdminController@updatePurpose');
Route::get('/admin/removePurpose', 'AdminController@removePurpose');
Route::post('/admin/searchedPurpose', 'AdminController@searchedPurpose');

Route::get('/admin/removeContextSeverity/{id}', 'AdminController@removeContextSeverity')->name('admin.removeContextSeverity');
Route::post('/admin/addSeverity', 'AdminController@addSeverity')->name('admin.addSeverity');
Route::get('/admin/editSeverity/{id}', 'AdminController@editSeverity')->name('admin.editSeverity');
Route::post('/admin/updateSeverity', 'AdminController@updateSeverity')->name('admin.updateSeverity');

Route::post('/admin/addRequestReviewer', 'AdminController@addRequestReviewer');
Route::get('/admin/editRequestReviewer', 'AdminController@editRequestReviewer');
Route::post('/admin/updateRequestReviewer', 'AdminController@updateRequestReviewer');
Route::get('/admin/removeRequestReviewer', 'AdminController@removeRequestReviewer');

Route::get('/admin/viewPriority', 'AdminController@viewPriority');
Route::post('/admin/addRequestApprover', 'AdminController@addRequestApprover');
Route::get('/admin/editRequestApprover', 'AdminController@editRequestApprover');
Route::post('/admin/updateRequestApprover', 'AdminController@updateRequestApprover');
Route::get('/admin/removeRequestApprover', 'AdminController@removeRequestApprover');

Route::get('/admin/changeCategories', 'AdminController@viewchangecategory');
Route::post('/admin/addcat', 'AdminController@addCat');
Route::get('/admin/editcat', 'AdminController@editCat');
Route::post('/admin/updatecat', 'AdminController@updateCat');
Route::get('/admin/removecat', 'AdminController@removeCat');
Route::post('/admin/searchedcat', 'AdminController@searchedCat');

//admin product/service
Route::get('/admin/viewproduct', 'AdminController@viewproduct');
Route::post('/admin/product/excel-upload', 'AdminController@productExcelUpload')->name('admin.product.excel_upload');
Route::get('/admin/editproduct', 'AdminController@editproduct');
Route::post('/admin/updateproduct', 'AdminController@updateproduct');
Route::get('/admin/removeproduct', 'AdminController@removeproduct');
Route::get('/admin/searchedproduct', 'AdminController@searchedproduct');
Route::post('/admin/searchedproduct', 'AdminController@searchedproduct');
Route::post('/admin/addproduct', 'AdminController@addproduct');

//admin customize report
Route::get('/admin/dynamic/audit/reports', 'AdminController@auditdynamicreports')->name('admin.audit.dynamicreports');
Route::post('/admin/audit/uploadreport', 'AdminController@uploadauditreport')->name('admin.audit.uploadreport');

//admin client logo upload - profile
Route::get('/admin/viewclientprofile', 'AdminController@clientviewprofile')->name('admin.client.profile');
Route::post('/admin/uploadclientprofile', 'AdminController@uploadclientlogo')->name('admin.client.uploadlogo');
Route::get('/admin/security/MFA', 'Admin\Security\MFAController@index')->name('admin.security.mfa');
Route::post('/admin/security/enable-disable-MFA', 'Admin\Security\MFAController@enableDisableMFA')->name('admin.security.enableDisableMFA');
//admin routes for incident reports
Route::get('/admin/addupdate/incidentelements', 'AdminController@add_update_IncidentElements');
Route::post('/admin/addIncidentType', 'AdminController@addIncidentType');
Route::post('/admin/updatedelete/incidenttype', 'AdminController@updatedeleteIncidentType');

Route::post('/admin/addIncidentPrevention', 'AdminController@addIncidentPrevention');
Route::post('/admin/updatedelete/incidentprevention', 'AdminController@updatedeleteIncidentPrevention');

Route::post('/admin/addIncidentProtection', 'AdminController@addIncidentProtection');
Route::post('/admin/updatedelete/incidentprotection', 'AdminController@updatedeleteIncidentProtection');

//waste routes for admin
Route::get('/admin/wastedesc', 'AdminController@wastedesc')->name('admin.waste.wastedesc');
Route::post('/admin/addwasteType', 'AdminController@addwasteType')->name('admin.waste.addwastetype');
Route::get('/admin/editwaste/{id}', 'AdminController@editwaste');
Route::post('/admin/updatewaste', 'AdminController@updatewaste');
Route::get('/admin/removewasteType/{id}', 'AdminController@removewasteType');
Route::get('/admin/editwasteType/{id}', 'AdminController@editwasteType');
Route::post('/admin/updatewasteType', 'AdminController@updatewasteType');
Route::post('/admin/ajaxwastetarget', 'AdminController@ajaxWasteTarget')->name('admin.waste.ajaxwastetarget');
Route::get('/admin/removewasteTarget/{id}', 'AdminController@removewasteTarget')->name('admin.waste.removewasteTarget');
Route::get('/admin/editwasteTarget/{id}', 'AdminController@editwasteTarget')->name('admin.waste.editwastetarget');
Route::post('/admin/updatewasteTarget', 'AdminController@updatewasteTarget')->name('admin.waste.updatewastetarget');
Route::get('/admin/wastetypes', 'AdminController@showWasteTypes')->name('admin.waste.showWasteType');
Route::get('/admin/wastetargets', 'AdminController@showWasteTarget')->name('admin.waste.showWasteTarget');
Route::get('/admin/wasteunits', 'AdminController@showWasteUnit')->name('admin.waste.showWasteUnit');
Route::get('/admin/wastedisposalmethods', 'AdminController@showWasteDisposalMethod')->name('admin.waste.showWasteDisposalMethod');
Route::get('/admin/editwasteUnit/{id}', 'AdminController@editwasteUnit');
Route::post('/admin/updatewasteUnit', 'AdminController@updatewasteUnit');
Route::get('/admin/removewasteUnit/{id}', 'AdminController@removewasteUnit');
Route::post('/admin/addwasteUnit', 'AdminController@addwasteUnit')->name('admin.waste.addwasteunit');

Route::get('/admin/editwasteDisposalMethod/{id}', 'AdminController@editwasteDisposalMethod');
Route::post('/admin/updatewasteDisposalMethod', 'AdminController@updatewasteDisposalMethod');
Route::get('/admin/removewasteDisposalMethod/{id}', 'AdminController@removewasteDisposalMethod');
Route::post('/admin/addwasteDisposalMethod', 'AdminController@addwasteDisposalMethod')->name('admin.waste.addwastedisposalmethod');

//vendor routes for admin
Route::get('/admin/vendorEvaluationDetails', 'AdminController@vendorEval');
Route::post('/admin/vendor/excel-upload', 'AdminController@vendorExcelUpload')->name('admin.vendor.excel_upload');
Route::get('/admin/vendorEvaluationResult', 'AdminController@vendorResult');
Route::get('/admin/editres', 'AdminController@editres');
Route::post('/admin/updateres', 'AdminController@updateRes');

Route::get('/admin/AddQstns', 'AdminController@addqstns');
Route::post('/admin/insertqstns', 'AdminController@insertqstns');
Route::get('/admin/editQstns', 'AdminController@editQstns');
Route::post('/admin/updateqstns', 'AdminController@updateQstns');
Route::get('/admin/CarryQstnID', 'AdminController@CarryQstnID');

Route::get('/admin/removeQstn', 'AdminController@removeQstn');
//audit root cause and corrective actions
Route::get('/admin/addcorrectiverootcorrective', 'AdminController@addcorrectiverootcorrective')->name('add_corrective_add_root_corrective');
Route::post('/admin/insertcorrectiverootcorrective', 'AdminController@insertcorrectiverootcorrective')->name('corrective_insert_root_corrective');
Route::get('/admin/editcorrectiverootcorrective/{id}', 'AdminController@editcorrectiverootcorrective')->name('edit_corrective_root_corrective');
Route::post('/admin/updatecorrectiverootcorrective/', 'AdminController@updatecorrectiverootcorrective')->name('update_corrective_root_corrective');
Route::get('/admin/deletecorrectiverootcorrective/{id}', 'AdminController@deletecorrectiverootcorrective')->name('delete_corrective_root_corrective');
//forgot password routes
Route::post('/verify/email', 'Auth\ForgotPasswordController@verifyEmails')->name('verifyemail');
Route::post('/reset/password', 'Auth\ResetPasswordController@verifyVerificationcode')->name('resetpassword');
Route::post('/reset/password/confirm', 'Auth\ResetPasswordController@confirmResetPassword')->name('confirmreset');

//for converting to pdf
Route::post('/user/convert/file/{tvalue}', 'UserController@convertpdf');
//admin routes
//employees details
//Route::get('/admin/employeedetails', 'AdminController@employeedetails');
Route::get('/admin/employeelist', 'AdminController@employeedetails');
Route::get('/admin/addemployees', 'AdminController@addemployeedetails');
Route::post('/admin/upload/empdetails', 'AdminController@add_to_database');
//Route::post('/admin/addmore/employeedetails', 'AdminController@add_more_empdetails');
Route::post('/admin/addmore/employeedetails', 'AdminController@more_empdetails');
Route::get('/admin/displayeditEmp/{id}/edit', 'AdminController@displayeditEmp');
Route::post('/admin/updateEmp/{id}', 'AdminController@updateEmp');
Route::get('/admin/deleteemployee', 'AdminController@deleteemployeeID');
Route::get('/admin/removeEmp', 'AdminController@removeEmp');
Route::post('/admin/multipleempaction', 'AdminController@multipleaction');
Route::post('/admin/multipleempupdate', 'AdminController@updateallEmp');

//calibration master list
//Route::get('/admin/calibrationlist', 'AdminController@calibrationdetails');
Route::get('/admin/calibrationlist', 'AdminController@calibrationindex');
Route::get('/admin/addcalibration', 'AdminController@addcalibrationlist');
Route::post('/admin/upload/caldetails', 'AdminController@insertcal');
Route::post('/admin/multiplecalaction', 'AdminController@multiplecalaction');
Route::post('/admin/calinstupdate', 'AdminController@updateallcal')->name('updatecalmastinst');

//document master list
Route::get('/admin/documentmasterlist', 'AdminController@documentindex');
Route::get('/admin/adddocumentmaster', 'AdminController@adddocumentmaster');
Route::post('/admin/upload/docmasterdetails', 'AdminController@insertdocmaster');
Route::post('/admin/multipledocmasteraction', 'AdminController@multipledocmasteraction');

//Occupational Health
//Category
Route::get('/admin/health/category', 'AdminController@healthcategorylist');
Route::post('/admin/health/addcategory', 'AdminController@addhealthcategory');
Route::get('/admin/health/editcategory/{id}', 'AdminController@edithealthcategory');
Route::post('/admin/health/updatehealthcategory', 'AdminController@updatehealthcategory');
Route::get('/admin/health/deletecategory/{id}', 'AdminController@deletehealthcategory');
//Treatments
Route::get('/admin/health/treatment', 'AdminController@healthtreatment');
Route::post('/admin/health/addtreatment', 'AdminController@addtreatment');
Route::get('/admin/health/edittreatment/{id}', 'AdminController@edittreatment');
Route::post('/admin/health/updatetreatment', 'AdminController@updatetreatment');
Route::get('/admin/health/deletetreatment/{id}', 'AdminController@deletetreatment');
//Injury Work Related Case type
Route::get('/admin/health/injury', 'AdminController@viewinjury');
Route::post('/admin/health/addinjurywork', 'AdminController@addinjurywork');
Route::get('/admin/health/editinjurywork/{id}', 'AdminController@editinjurywork');
Route::post('/admin/health/updateinjurywork', 'AdminController@updateinjurywork');
Route::get('/admin/health/deleteinjurywork/{id}', 'AdminController@deleteinjurywork');
//Illness Non-Work Related Case type
Route::get('/admin/health/illness', 'AdminController@viewillness');
Route::post('/admin/health/addillnessnonwork', 'AdminController@addillnessnonwork');
Route::get('/admin/health/editillnonwork/{id}', 'AdminController@editillnonwork');
Route::post('/admin/health/updateillnessnonwork', 'AdminController@updateillnessnonwork');
Route::get('/admin/health/deleteillnonwork/{id}', 'AdminController@deleteillnonwork');
//Nature of Injury
Route::get('/admin/health/injurynature', 'AdminController@viewinjurynature');
Route::post('/admin/health/addinjurynature', 'AdminController@addinjurynature');
Route::get('/admin/health/editinjurynature/{id}', 'AdminController@editinjurynature');
Route::post('/admin/health/updateinjurynature', 'AdminController@updateinjurynature');
Route::get('/admin/health/deleteinjurynature/{id}', 'AdminController@deleteinjurynature');
//Nature of Illness
Route::get('/admin/health/illnessnature', 'AdminController@viewillnessnature');
Route::post('/admin/health/addillnessnature', 'AdminController@addillnessnature');
Route::get('/admin/health/editillnessnature/{id}', 'AdminController@editillnessnature');
Route::post('/admin/health/updateillnessnature', 'AdminController@updateillnessnature');
Route::get('/admin/health/deleteillnessnature/{id}', 'AdminController@deleteillnessnature');

/**---Admin routes for Management review ---*/
//Agenda type
Route::get('/admin/management/agendatypes', 'AdminController@managementagendatypes')->name('management.agendatypes');
Route::post('/admin/management/addagendatypes', 'AdminController@addagendatypes');
Route::get('/admin/management/editagendatypes/{id}', 'AdminController@editagendatypes');
Route::post('/admin/management/updateagendatype', 'AdminController@updateagendatype');

//Agenda
Route::get('/admin/management/agenda', 'AdminController@managementagenda')->name('management.agenda');
Route::get('/admin/management/addagenda', 'AdminController@addagenda')->name('management.addagenda');
Route::post('/admin/management/insertagenda', 'AdminController@insertagenda');
Route::get('/admin/management/editagenda/{id}', 'AdminController@editagenda');
Route::post('/admin/management/updateagenda', 'AdminController@updateagenda');
Route::post('/admin/management/addagendaajax', 'AdminController@addagendaajax')->name('management.addagendaajax');
Route::get('/admin/management/agendapointdelete/{id}', 'AdminController@agendapointdelete')->name('management.agendapointdelete');

//Location
Route::get('/admin/management/location', 'AdminController@managementlocation')->name('management.location');
Route::post('/admin/management/addlocation', 'AdminController@addmanagementlocation');
Route::get('/admin/management/editlocation/{id}', 'AdminController@editmanagementlocation');
Route::post('/admin/management/updatelocation', 'AdminController@updatemanagementlocation');
Route::get('/admin/management/deletelocation/{id}', 'AdminController@deletemanagementlocation');

/**---Email Notification ---*/
Route::get('/admin/emailnotificationlist', 'AdminController@emailnotificationlist')->name('admin.emailnotificationlist');
Route::post('/admin/emailnotification/addemailnotificationajax', 'AdminController@addemailnotificationajax')->name('admin.addemailnotificationajax');
Route::post('/admin/emailnotification/selectemaildetails', 'AdminController@selectemaildetails')->name('admin.selectemaildetails');
Route::get('/admin/emailnotification/deleteemailusers', 'AdminController@deleteemailusers')->name('admin.deleteemailusers');
// Route::get('/admin/emailnotification/add', 'AdminController@addemailnotification')->name('admin.addemailnotification');
// Route::post('/admin/emailnotification/insert', 'AdminController@insertemailnotification')->name('admin.insertemailnotification');
Route::get('/admin/emailnotification/edit/{id}', 'AdminController@editemailnotification')->name('admin.editemailnotification');
Route::post('/admin/emailnotification/update', 'AdminController@updateemailnotification')->name('admin.updateemailnotification');
Route::get('/admin/emailnotification/blocked/{id}', 'AdminController@blockemailnotification')->name('admin.blockemailnotification');
Route::get('/admin/emailnotification/unblock/{id}', 'AdminController@unblockemailnotification')->name('admin.unblockemailnotification');

//Admin System Management
Route::group(['prefix' => '/admin/system-management', 'as' => 'admin.systemManagement'], function() {
    Route::get('backup', 'Admin\SystemManagement\BackupController@index')->name('.backup');
    Route::post('database-backup', 'Admin\SystemManagement\BackupController@databaseBackup')->name('.databaseBackup');
    Route::post('files-backup', 'Admin\SystemManagement\BackupController@filesBackup')->name('.filesBackup');
    Route::get('backup/download/{id}', 'Admin\SystemManagement\BackupController@download')->name('.backup.download');
    Route::post('backup/delete', 'Admin\SystemManagement\BackupController@delete')->name('.backup.delete');
});

//Customer satisfaction module (admin panel)
Route::group(['prefix' => '/admin/customer-satisfaction', 'as' => 'admin.customer.'], function () {
    Route::get('evaluationDetails', 'AdminController@customerEvaluationDetails')->name('evaluationDetails');
    Route::get('deleteQstn/{id}', 'AdminController@deleteCustomerQstn')->name('deleteQstn');
    Route::get('editQstn/{id}', 'AdminController@editCustomerQstn')->name('editQstn');
    Route::post('updateQstn', 'AdminController@updateCustomerQstn')->name('updateQstn');
    Route::get('addQstn/{type}', 'AdminController@addCustomerQstn')->name('addQstn');
    Route::post('insertQstn', 'AdminController@insertCustomerQstn')->name('insertQstn');
});
//ajax for show profile pictures
Route::get('/show/{id}/{modelname}', 'BaseController@getProfilePic')->name('profileImage');
Route::get('/upload-file', 'BaseController@showFileUpload')->name('showFileUpload')->middleware('auth:admin');
Route::post('/upload-file', 'BaseController@fileUpload')->name('fileUpload');

Route::group(['prefix' => '/admin/complaint', 'as' => 'admin.complaint.'], function () {
    Route::get('/category', 'AdminController@indexComplaint')->name('category');
    Route::post('/addcategory','AdminController@addComplaintCategory');
    Route::post('/editcategory','AdminController@editComplaintCategory');
    Route::post('/deletecategory','AdminController@deleteComplaintCategory');
    Route::post('/addcategoryexcel','AdminController@addCategoryExcel');
    Route::post('/addcomplaintunit','AdminController@addComplaintUnit')->name('addcomplaintunit');
    Route::get('/unit', 'AdminController@complaintUnit')->name('unit');
    Route::post('/deleteunit','AdminController@deleteComplaintUnit')->name('deleteunit');
    Route::post('/editunit','AdminController@editComplaintUnit')->name('editunit');
    Route::post('/update-complaint-category-plant-dependency','AdminController@updateComplaintCategoryPlantDependency')->name('updateComplaintCategoryPlantDependency');
    Route::post('/update-complaint-category-plant-mapping','AdminController@updateComplaintCategoryPlantMapping')->name('updateComplaintCategoryPlantMapping');

    Route::get('/complaint-form-configuration', 'Admin\Complaint\ComplaintFormConfigurationController@index')->name('complaintFormConfiguration');
    Route::post('/complaint-form-configuration', 'Admin\Complaint\ComplaintFormConfigurationController@store')->name('saveComplaintFormConfiguration');

    Route::resource('/source-of-issue', 'Admin\Complaint\SourceOfIssueController');
});

Route::group(['prefix' => '/admin/custom_field', 'as' => 'admin.custom_field.'], function () {
    Route::get('/custom-field-configuration', [CustomFieldController::class, 'index'])->name('custom_field_configuration');
    Route::get('/get-target-display-page', [CustomFieldController::class, 'getTargetDisplayPage'])->name('get_target_display_page');
    Route::post('/custom-fields', [CustomFieldController::class, 'store']);
    Route::get('/custom-fields-edit/{id}', [CustomFieldController::class, 'edit']);
    Route::post('/custom-fields/update', [CustomFieldController::class, 'update']);
    Route::delete('/custom-fields/{id}', [CustomFieldController::class, 'destroy']);
    Route::get('/custom-fields-preview',[CustomFieldController::class,'preview']);
});
Route::group(['prefix' => '/admin/compliance', 'as' => 'admin.compliance.'], function () {
    Route::get('/index', 'AdminController@indexCompliance')->name('index');
    //Regulatory
    Route::post('/addregulatory','AdminController@addComplianceRegulatory');
    Route::post('/editregulatory','AdminController@editComplianceRegulatory');
    Route::delete('/deleteregulatory/{id}','AdminController@deleteComplianceRegulatory');
    Route::post('/checkuniquerequlatory','AdminController@checkUniqueRegulatory');
    Route::get('/viewregulatory/{id}','AdminController@viewComplianceDetails');
    Route::get('/edit-regulatory-modal/{id}','AdminController@getEditRegulatoryModal');
    //Legal Document
    Route::post('/addlegaldocument','AdminController@addLegalDocument');
    Route::delete('/deletelegaldocument/{id}','AdminController@deleteLegalDocument');
    Route::post('/editlegaldocument','AdminController@editLegalDocument');
    Route::post('/checkuniquelegaldocument','AdminController@checkUniqueLegalDocument');
    Route::get('/edit-legal-document-modal/{id}','AdminController@getEditLegalDocumentModal');
    Route::post('/review-doc','AdminController@reviewLegalDocument');
    Route::post('/review-auth','AdminController@reviewRegulatory');
    Route::post('/review-doc-multiple','AdminController@reviewLegalDocumentMultiple');
    Route::post('/review-auth-multiple','AdminController@reviewRegulatoryMultiple');

});
Route::get('/compliance/review-regulatory/{id}/{action}/{token}/{admin_id}','ApprovalController@reviewRegulatory')->name('regulatoryapproval.process');
Route::get('/compliance/review-legal-document/{id}/{action}/{token}/{admin_id}','ApprovalController@reviewLegalDocument')->name('legaldocument.approval.process');
Route::get('/public/nearmiss/report','ApprovalController@nearMissAdd')->name('nearmiss.add');
Route::post('/public/nearmiss/report/submit','ApprovalController@nearMissAddSubmit')->name('nearmiss.submit');
Route::post('/public/nearmiss/report/employeeid-verification','ApprovalController@nearMissEmployeeidVerification')->name('nearmiss.employee.verify');
// Route::any('/ckfinder/connector', '\CKSource\CKFinderBridge\Controller\CKFinderController@requestAction')
//     ->name('ckfinder_connector');

// Route::any('/ckfinder/browser', '\CKSource\CKFinderBridge\Controller\CKFinderController@browserAction')
//     ->name('ckfinder_browser');
// Route::group(['middleware' => ['CustomCKFinderAuth']], function () {
//     Route::any('/ckfinder/connector', 'CKSource\CKFinderBridge\Controller\CKFinderController@requestAction')->name('ckfinder_connector');
//     Route::any('/ckfinder/browser', 'CKSource\CKFinderBridge\Controller\CKFinderController@browserAction')->name('ckfinder_browser');
// });
//-------------------------------------------------------Admin And USER Related Routes List END -----------------------------------------------------------------//

/**\
-------------------------------------------------Please write the admin panel related routes above this section.-------------------------------------------------*
 */
/**
 * Module Routes Lists Start --------------------- USER PANEL - Modules------------------------------------------------------------------------------------
 *
 */
Route::middleware(['track.activity'])->group(function () {
    //risk
    Route::get('/user/risks/ai/organization', 'RiskAiOrganizationController@index')->name('risk.ai.organization');
    Route::post('/user/risks/ai/organization/profile', 'RiskAiOrganizationController@saveProfile')->name('risk.ai.organization.profile');
    Route::post('/user/risks/ai/organization/notes', 'RiskAiOrganizationController@addNote')->name('risk.ai.organization.notes');
    Route::post('/user/risks/ai/organization/notes/{id}/withdraw', 'RiskAiOrganizationController@withdrawNote')->name('risk.ai.organization.withdraw');
    Route::post('/user/risks/ai/feedback', 'RiskAiOrganizationController@feedback')->name('risk.ai.feedback');
    Route::post('/user/risks/ai/feedback/{id}/review', 'RiskAiOrganizationController@reviewFeedback')->name('risk.ai.feedback.review');
    Route::post('/user/risks/ai/draft', 'RiskAiController@draft')->name('risk.ai.draft');
    Route::get('/user/riskmgmt', 'RiskController@regrisk')->name('risk.index');
    Route::get('/user/riskmgmt/{id}/detail', 'RiskController@regriskDetail')->name('risk.detail');
    Route::get('/user/riskmgmt/{id}/detail_pdf', 'RiskController@regriskDetailPdf')->name('risk.detail.pdf');
    Route::group(['prefix' => '/user/risks', 'as' => 'risk.'], function () {
        Route::get('/{id}/detail', 'RiskController@show')->name('show');
        Route::get('/{id}/create-mitigation', 'RiskController@createMitigation')->name('create.mitigation');
        Route::get('/closedrisks', 'RiskController@closedrisks')->name('closedrisks');
        Route::get('/addrisk', 'RiskController@addrisk')->name('addrisk');
        Route::post('/submit', 'RiskController@insertrisk')->name('submit');
        Route::get('/resubmitrisk', 'RiskController@resubmit')->name('resubmitrisk');
        Route::post('/resubmitrisk', 'RiskController@resubmitrisk')->name('resubmitrisk_post');
        Route::get('/analysis', 'RiskController@analysis')->name('analysis');
        Route::get('/updaterisk', 'RiskController@adds')->name('updaterisk')->middleware('protect.riskInitialReview');
        Route::post('/updaterisk', 'RiskController@updaterisk')->name('updaterisk_post')->middleware('protect.riskInitialReview');
        Route::get('/disapprove', 'RiskController@disapprove')->name('disapprove');
        Route::post('/disapproving', 'RiskController@disapproving')->name('disapprovingg');
        Route::get('/ownrespo', 'RiskController@own')->name('ownrespo');
        Route::post('/ownrespo', 'RiskController@responsive')->name('ownrespo_post');
        Route::get('/hundred', 'RiskController@hundred')->name('hundred');
        Route::get('/five', 'RiskController@five')->name('five');
        Route::get('/two', 'RiskController@two')->name('two');
        Route::get('/zero', 'RiskController@zero')->name('zero');
        Route::get('/riskreview', 'RiskController@upd')->name('riskreview');
        Route::post('/riskreview', 'RiskController@updatereview')->name('riskreview_post');
        Route::get('/closure', 'RiskController@closurereview')->name('closure');
        Route::post('/closure', 'RiskController@closure')->name('closure_post');
        Route::get('/needmoreaction', 'RiskController@needmoreaction')->name('needmoreaction');
        Route::get('/closed', 'RiskController@closed')->name('closed');
        Route::get('/reopenrisk', 'RiskController@reopenrisk')->name('reopenrisk');
        Route::post('/reopenrisk', 'RiskController@reopen')->name('reponrisk_post');
        Route::get('/delete', 'RiskController@delete')->name('deleterisk');
        Route::get('/editreq', 'RiskController@editreq')->name('editrisk');
        Route::post('/editrequest', 'RiskController@editrequest')->name('editrequest');
        Route::get('useredit/{id}', 'RiskController@useredit')->name('usereditrisk');
        Route::post('usereditsubmit', 'RiskController@usereditsubmit')->name('usereditrisk_post');
        Route::get('/riskhistory', 'RiskController@riskhistory')->name('riskhistory');
        Route::get('/addopportunity/{id}', 'RiskController@addriskopportunity')->name('addriskopportunity');
        Route::post('/opportunitysubmit', 'RiskController@insertriskopportunity')->name('insertriskopportunity');
        Route::get('/changeriskowner/{id}', 'RiskController@changeriskowner')->name('changeriskowner');
        Route::post('/riskownersubmit', 'RiskController@updateriskowner')->name('updateriskowner');
        Route::get('/notification', 'RiskController@notification')->name('notification');
        Route::get('/export-data/{format}', 'RiskController@exportData')->name('exportData');
    });
    Route::post('/user/riskmgmt/displayriskgroupnameajax', 'RiskController@displayriskgroupnameajax')->name('risk.displayriskgroupnameajax');
    Route::post('/user/riskmgmt/displayriskgroupdetailsajax', 'RiskController@displayriskgroupdetailsajax')->name('risk.displayriskgroupdetailsajax');

    // RACM
    Route::group(['prefix' => '/user/riskmgmt/racm', 'as' => 'racm.'], function () {
        Route::get('/', 'RacmController@index')->name('index');
        Route::get('/create', 'RacmController@create')->name('create');
        Route::post('/', 'RacmController@store')->name('store');
        Route::post('/{id}/edit', 'RacmController@edit')->name('edit');
        Route::post('/{id}/update', 'RacmController@update')->name('update');
        Route::delete('/{id}/delete', 'RacmController@destroy')->name('destroy');
        Route::post('/request-add-reviewers-approver','RacmController@addRequestReviewerApprover');
        Route::get('/review/{ids}/{reviewer}','RacmController@reviewRacms');
        Route::get('/approve/{ids}','RacmController@approveRacms');
        Route::post('/review-submit','RacmController@reviewRacmsSubmit')->name('reviewed');
        Route::post('/approve-submit','RacmController@approveRacmsSubmit')->name('approved');
        Route::post('/review-reject-reason','RacmController@reviewRejectReason');
        Route::post('/approve-reject-reason','RacmController@approveRejectReason');
    });

    // Mitigation
    Route::group(['prefix' => '/user/riskmgmt/mitigations', 'as' => 'mitigations.'], function () {
        Route::get('/', 'MitigationController@index')->name('index');
        Route::post('/', 'MitigationController@store')->name('store');
        Route::get('/create', 'MitigationController@create')->name('create');
        Route::get('/{id}', 'MitigationController@edit')->name('edit');
        Route::get('/show/{id}', 'MitigationController@detail')->name('detail');
        Route::get('/export-pdf/{id}', 'MitigationController@detailPdf')->name('detail.pdf');
        Route::put('/{id}', 'MitigationController@update')->name('update');
        Route::delete('/{id}', 'MitigationController@destroy')->name('destroy');
        Route::post('/{id}/change-progress', 'MitigationController@changeProgress')->name('change.progress');
        Route::get('/{id}/closure-review', 'MitigationController@closureReview')->name('closure.review');
        Route::put('/{id}/close', 'MitigationController@closeMitigation')->name('close');
        Route::get('/{id}/reopen', 'MitigationController@reopen')->name('reopen');
    });
    /* - - - -  - - - - - - - - - - - - - - Start Comments For Risk - - - - - - - - - - - - - - - - - - - - -  */
    Route::group(['prefix' => '/user/riskmgmt/comment', 'as' => 'riskcomment.'], function () {
        Route::post('add', 'RiskCommentController@store')->name('store');
        Route::get('delete/{id}', 'RiskCommentController@destroy')->name('destroy');
        Route::get('delete-reply/{id}', 'RiskCommentController@deleteReply')->name('delete_reply');
        Route::post('edit-comment','RiskCommentController@editComment');
    });
    /* - - - -  - - - - - - - - - - - - - - End Comments For Risk - - - - - - - - - - - - - - - - - - - - -  */

    //CALIBRATION MANAGEMENT
    //Master list of instruments(Register)
    Route::get('/user/calibrationmgmt', 'CalibrationController@index')->name('calibration.index');
    Route::get('/user/calibration/statuslist/{status?}', 'CalibrationController@statusviewlist')->name('calibration.viewlist');
    Route::get('/user/calibration/addinstruments', 'CalibrationController@addinstruments')->name('calibration.add.instruments')->middleware('protect.addInstrument');
    Route::post('/user/calibration/storeinstruments', 'CalibrationController@storeinstruments')->name('calibration.instruments.store')->middleware('protect.addInstrument');
    //Route::get('/user/calibration/addinstruments', 'CalibrationController@add');
    //Route::post('/user/calibration/insert', 'CalibrationController@insertInstruments');
    Route::post('/user/calibration/addInternalReport', 'CalibrationController@addInternalReport');
    Route::any('user/calibration/filtr', 'CalibrationController@filtr');
    Route::any('/user/calibration/internal', 'CalibrationController@internal')->name('int');
    Route::post('/user/calibration/internal2', 'CalibrationController@internal2');
    Route::any('/user/calibration/editins', 'CalibrationController@editins');
    Route::any('/user/calibration/checkinstrumentno', 'CalibrationController@checkInstrumentNo')->name('calibration.instruments.checkinstrumentno');
    Route::any('/user/calibration/instupdate', 'CalibrationController@updateins');
    Route::post('/user/calibration/addcalibrationattachments', 'CalibrationController@addcalibrationattachments')->name('addcalibrationattachments');
    Route::get('/user/calibration/deleteexternalfiles/{id}', 'CalibrationController@deleteexternalfiles');
    Route::post('/user/calibration/addexternalattachments', 'CalibrationController@addexternalattachments')->name('calibration.addexternalattachments');

    Route::get('/user/calibration/cal_log', 'CalibrationController@cal_log');
    Route::get('/user/calibration/cal_logext', 'CalibrationController@cal_logext');
    Route::get('/user/calibration/intrep', 'CalibrationController@intrep');

    Route::get('/user/calibration/viewstatus', 'CalibrationController@viewstatus')->name('calibration.view.status');
    Route::post('/user/calibration/updatestatus', 'CalibrationController@updatestatus');
    Route::get('/user/calibration/disprep', 'CalibrationController@disprep');
    Route::get('/user/calibration/a', 'CalibrationController@a');
    Route::get('/user/calibration/update1', 'CalibrationController@update1')->name('updatecal');

    Route::post('/user/calibration/update2', 'CalibrationController@update2');

    Route::post('/user/calibration/addriskinfo', 'CalibrationController@addriskinfo');
    Route::get('report/print-pdf', 'CalibrationController@printPDF');
    Route::get('report/print-pdfext', 'CalibrationController@printPDF_calext');
    Route::get('report/print-pdf1', 'CalibrationController@printInternalrep');
    Route::get('user/calibration/changereq', 'CalibrationController@changereq')->name('change');
    //Route::get('user/calibration/delete', 'CalibrationController@delete')->name('deletecal');
    Route::post('user/calibration/delete', 'CalibrationController@delete')->name('deletecal');
    Route::get('/user/calibration/analysis', 'CalibrationController@analysis');
    Route::post('/user/calibration/change-status', 'CalibrationController@changeStatus')->name('calibration.change.status');
    Route::post('/user/calibration/change-status-obsolete', 'CalibrationController@changeStatusObsolete')->name('calibration.change.status.obsolete');
    Route::get('/user/calibration/under-list', 'CalibrationController@getUnderCalibrationConList')->name('calibration.user.list');
    Route::post('/user/calibration/store-target-id', 'CalibrationController@storeTargetId')->name('calibration.user.store.target.id');
    Route::get('/user/calibration/update-status-form', 'CalibrationController@displayUpdateStatusForm')->name('calibration.user.show.update.status');
    Route::post('/user/calibration/{id}/calculate-duedate', 'CalibrationController@calculateDueDate')->name('calibration.user.calculate.duedate');
    Route::post('/user/calibration/status-update-handling', 'CalibrationController@handleStatusUpdates')->name('calibration.user.update.update.by.duedate');
    Route::post('/user/calibration/send-mail-to-reviewer', 'CalibrationController@sendMailToReviewer')->name('calibration.user.send.mail.to.reviewer');
    Route::get('/user/calibration/{id}/{type}/show', 'CalibrationController@show')->name('calibration.show');
    Route::get('/user/calibration/{id}/{pagename}/add-attachment', 'CalibrationController@addAttachment')->name('calibration.add.attachment');
    //Route::post('user/calibration/autocomplete', 'CalibrationController@autocomplete')->name('autocomplete');
    //Environmental aspects

    //ADD
    Route::get('/user/enviaspects/home', 'EnviaspectsController@index')->name('enviaspectsdashboard');
    Route::get('/user/enviaspects/add', 'EnviaspectsController@addenviaspects')->name('add');
    Route::post('/user/enviaspects/displayimpactdetailajax', 'EnviaspectsController@displayimpactdetailajax')->name('enviaspects.displayimpactdetailajax');
    Route::post('/user/enviaspects/adddcr', 'EnviaspectsController@adddcr')->name('adddcr');
    //edit location and activity
    Route::get('/user/enviaspects/newedit', 'EnviaspectsController@editnew');
    Route::post('/user/enviaspects/editnew', 'EnviaspectsController@newedit');

    Route::get('/user/enviaspects/addaspect', 'EnviaspectsController@addaspect');
    Route::post('/user/enviaspects/adddcrr', 'EnviaspectsController@adddcrr');

    Route::get('/user/enviaspects/folder', 'EnviaspectsController@folder')->name('enviaspect.folder');
    Route::post('/user/enviaspects/createfolder', 'EnviaspectsController@createfolder');
    //mr edit

    Route::post('/user/enviaspects/editrequest_envi', 'EnviaspectsController@editrequest_envi');
    Route::get('/user/enviaspects/editenvi', 'EnviaspectsController@editenvi')->name('editenvi');
    //List
    Route::get('/user/enviaspects/listofaspects/{department}', 'EnviaspectsController@regenviaspects')->name('enviaspects.dep');
    Route::get('/user/enviaspects/all/{type?}', 'EnviaspectsController@allAspects')->name('enviaspects.all');

    Route::get('/user/enviaspects/listofaspects1/{location}', 'EnviaspectsController@regenviaspects1');


    Route::get('/url/enviaspects/listofaspects', 'EnviaspectsController@viewenviaspects');
    Route::any('user/enviaspects/filtr', 'EnviaspectsController@filtr');
    Route::get('/user/enviaspects/review', 'EnviaspectsController@adds');
    Route::post('/user/enviaspects/review', 'EnviaspectsController@updateaspects');

    //Approve Aspect
    Route::get('/user/enviaspects/approve/{id}', 'EnviaspectsController@approve')->name('user.enviaspects.approve');
    Route::post('/user/enviaspects/approve', 'EnviaspectsController@doApprove')->name('user.enviaspects.add_approve');

    Route::get('/user/enviaspects/secondaryreview', 'EnviaspectsController@addsecondary');
    Route::post('/user/enviaspects/secondaryreview', 'EnviaspectsController@updatesecondary');
    Route::get('/user/enviaspects/resubmit', 'EnviaspectsController@resubmit');
    Route::post('/user/enviaspects/resubmit', 'EnviaspectsController@updateresubmit');
    Route::get('/user/enviaspects/propose', 'EnviaspectsController@propose');
    Route::post('user/enviaspects/propose_change', 'EnviaspectsController@propose_change')->name('proposechanges');
    Route::get('/user/enviaspects/action/{id}', 'EnviaspectsController@action')->name('user.enviaspect.action');
    Route::post('/user/enviaspects/control_pro', 'EnviaspectsController@control_pro')->name('user.enviaspect.control_pro');

    //DELETE  Edited: Team_bngl
    Route::get('/user/enviaspects/deleteaspect', 'EnviaspectsController@deleteaspect');
    Route::post('/user/enviaspects/deleteaspect/confirm/', 'EnviaspectsController@deleteaspectconf')->name('aspect.delete');
    Route::get('/user/enviaspects/deleteactivity', 'EnviaspectsController@deleteactivity')->name('delete.activity');

    //Analysis
    Route::get('/user/enviaspects/analysis', 'EnviaspectsController@analysis');
    Route::any('user/enviaspects/search', 'EnviaspectsController@search');

    //delete button in register
    Route::get('user/enviaspects/delete', 'EnviaspectsController@delete')->name('deleteenviaspects');

    //Objectives

    //Add
    Route::get('/user/objectives/addobjectives', 'ObjectivesController@addobjectives');
    Route::post('/user/objectives/submit', 'ObjectivesController@Insertobjectives');

    //list of objectives
    Route::get('/user/objectives/folders/{type?}', 'ObjectivesController@departmentsfolder')->name('user.objectives.folders');
    Route::get('user/objects/getsubfolder/{dept}/{type?}', 'ObjectivesController@departmentssubfolder');
    Route::get('user/objects/getsubdivsionfolder/{dept}/{type?}', 'ObjectivesController@departmentsDivsionSubfolder');
    Route::get('user/objects/getsub-subdivsionfolder/{id}/{dept}/{division}/{type?}', 'ObjectivesController@departmentsSubDivisionSubfolder');
    Route::get('/user/objectives/listofobjectives/{dept}/{type?}/{division?}/{subdivision?}', 'ObjectivesController@regobjectives');
    Route::get('user/objectives/approve/{type?}', 'ObjectivesController@addsapprove');
    Route::post('user/objectives/approve/{type?}', 'ObjectivesController@updateapprove');
    Route::get('/user/objectives/review/{type?}', 'ObjectivesController@adds');
    Route::post('/user/objectives/review/{type?}', 'ObjectivesController@updateobjectives');
    Route::get('user/objectives/reject/{type?}', 'ObjectivesController@reject');
    Route::post('user/objectives/rejecting/{type?}', 'ObjectivesController@rejecting')->name('rejecting');
    Route::get('/user/objectives/resubmit/{type?}', 'ObjectivesController@resubmit');
    Route::post('/user/objectives/resubmit/{type?}', 'ObjectivesController@resubmitobjectives');
    //edit objective by posted user
    Route::get('/user/objectives/editobjective/{type?}', 'ObjectivesController@editobjective');
    Route::post('/user/objectives/updateobjective/{type?}', 'ObjectivesController@updateobjective');
    //delete quarters
    Route::get('/user/objectives/deletequarters/{id}', 'ObjectivesController@deletequarters')->name('objective.deletequarters');
    //change responsible person
    Route::get('/user/objectives/changeresponsibleperson/{id}/{type?}', 'ObjectivesController@changeresponsibleperson')->name('objective.changeresponsibleperson');
    Route::post('/user/objectives/updateresponsibleperson/{type?}', 'ObjectivesController@updateresponsibleperson')->name('objective.updateresponsibleperson');
    //performance report
    Route::get('/user/objectives/objreport', 'ObjectivesController@objectivesreport');
    Route::get('/user/objectives/add/{type?}', 'ObjectivesController@add');
    Route::post('/user/objectives/add/{type?}', 'ObjectivesController@updatereport');
    Route::get('/user/objectives/viewreport/{id}', 'ObjectivesController@viewreport')->name('user.objectiveanalysis.getreports');
    Route::post('/user/objectives/selectmonthfreqgraph', 'ObjectivesController@selectmonthfreqgraph')->name('user.objectiveanalysis.selectmonthfreqgraph');
    Route::post('/user/objectives/selectquarterfreqgraph', 'ObjectivesController@selectquarterfreqgraph')->name('user.objectiveanalysis.selectquarterfreqgraph');
    Route::post('/user/objectives/updateCAR/{id}', 'ObjectivesController@updateCAR')->name('updateCAR');
    //delete
    Route::get('user/objective/{id}/detail', 'ObjectivesController@detail')->name('user.objective.detail');
    Route::get('user/objective/{id}/exportPdf', 'ObjectivesController@exportPdf')->name('user.objective.exportPdf');
    Route::get('user/objectives/delete', 'ObjectivesController@delete')->name('deleteobjectives');

    //edit
    //edit issues
    // Route::get('user/context/editreq_issues','ContextController@editreq_issues')->name('editissues');
    Route::post('/user/objectives/editrequest_obj/{type?}', 'ObjectivesController@editrequest_obj');
    Route::get('/user/objectives/editobj/{type?}', 'ObjectivesController@editobj')->name('editobj');

    //repeat objectives
    Route::get('/user/objectives/repeat/{type?}', 'ObjectivesController@repeat');
    Route::post('/user/objectives/repeatobjectives', 'ObjectivesController@repeatobjectives');

    //ajax for changing status of objectives while selecting on Approve or Review button
    Route::post('/user/objectives/ajax-review', 'ObjectivesController@ajaxReview')->name('objectives.ajaxReview');
    Route::post('/user/objectives/ajax-approve', 'ObjectivesController@ajaxApprove')->name('objectives.ajaxApprove');
    Route::post('user/objectives/change-approver','ObjectivesController@changeApprover')->name('objectives.changeApprover');
    Route::post('user/objectives/change-reviewer','ObjectivesController@changeReviewer')->name('objectives.changeReviewer');
    Route::get('/user/objectives/{id}/show', 'ObjectivesController@show')->name('objectives.show');

    // export objective register
    Route::post('/user/objectives/export-data', 'ObjectivesController@exportData')->name('objective.exportData');

    //display the suggestions
    Route::post('/user/objectives/displayobjectivegroupnameajax', 'ObjectivesController@displayobjectivegroupnameajax')->name('objectives.displayobjectivegroupnameajax');
    Route::post('/user/objectives/displayobjectivegroupdetailsajax', 'ObjectivesController@displayobjectivegroupdetailsajax')->name('objectives.displayobjectivegroupdetailsajax');
    //opportunity
    Route::group(['prefix' => '/user/opportunity', 'as' => 'opportunity.'], function () {
        Route::get('/accomplished', 'OpportunityController@accomplished')->name('oppaccomplished');
        Route::get('/addopportunity', 'OpportunityController@addopportunity')->name('addopp');
        Route::post('/submit', 'OpportunityController@InsertOpportunity')->name('insertopp');
        Route::get('/review/{id}', 'OpportunityController@review')->name('reviewopp');
        Route::get('/resubmit/{id}', 'OpportunityController@resubmit')->name('resubmitopp');
        Route::post('/resubmit', 'OpportunityController@resubmitopportunity')->name('resubmitopp_post');
        Route::get('/editreg/{id}', 'OpportunityController@editreg')->name('editregopp');
        Route::post('/editreg', 'OpportunityController@updatereg')->name('editregopp_post');
        Route::get('/update/{id}', 'OpportunityController@adds')->name('inireviewopp')->middleware('protect.opportunityInitialReview');
        Route::post('/update', 'OpportunityController@updateopportunity')->name('inireviewopp_post')->middleware('protect.opportunityInitialReview');
        Route::get('/moreaction/{id}', 'OpportunityController@addsreview')->name('secreviewopp');
        Route::post('/updatereview', 'OpportunityController@updatereview')->name('secreviewopp_post');
        Route::get('/closure/{id}', 'OpportunityController@closurereview')->name('closreviewopp');
        Route::post('/closure', 'OpportunityController@closure')->name('closreviewopp_post');
        Route::get('/disapprove/{id}', 'OpportunityController@disapprove')->name('disapproveopp');
        Route::post('/disapproving', 'OpportunityController@disapproving')->name('disapproveopp_post');
        Route::post('/close/{id}', 'OpportunityController@close')->name('closeopp');
        Route::get('/analysis', 'OpportunityController@analysis')->name('oppanalysis');
        Route::post('/ajaxanalysis', 'OpportunityController@ajaxAnalysis')->name('ajax_oppanalysis');
        Route::get('/delete/{id}', 'OpportunityController@delete')->name('deleteopportunity');
        Route::get('/editreq/{id}', 'OpportunityController@editreq')->name('editopportunity');
        Route::post('/editrequest', 'OpportunityController@editrequest')->name('editopportunity_post');
        Route::get('editopport/{id}', 'OpportunityController@editopport')->name('usereditopp');
        Route::post('updateopport', 'OpportunityController@submitopport')->name('usereditopp_post');
        Route::get('/{type?}', 'OpportunityController@register')->name('oppindex');
        //edit responsibility
        Route::get('/changeresponsib/{id}', 'OpportunityController@editresp')->name('changeoppresponsibility');
        Route::post('/submitresp', 'OpportunityController@submittresp')->name('submitoppresponsibility');
        Route::get('/view/{id}', 'OpportunityController@view')->name('viewopp');
        Route::get('/{id}/detail', 'OpportunityController@detail')->name('detail');
        Route::get('/{id}/detail_pdf', 'OpportunityController@detailPdf')->name('detail.pdf');
        Route::get('/export-data/{format}', 'OpportunityController@exportData')->name('exportData');
    });

    //--ACTION SYSTEM

    Route::get('/user/auditmgmt/{type?}', 'ActionController@viewlist')->name('action.viewlist');
    Route::get('/user/actionsystem/closedissues/{year}', 'ActionController@closedissues');
    Route::get('/user/actionsystem/folders', 'ActionController@yearfolder');
    // Route::get('user/actionsystem/getsubfolder/{dept}','ActionController@departmentssubfolder');
    Route::any('/user/actionsystem/view', 'ActionController@view');
    Route::get('/user/actionsystem/allclosedissue', 'ActionController@allclosedissue');
    Route::any('/user/actionsystem/view2', 'ActionController@view2');
    Route::get('/user/actionsystem/reopen', 'ActionController@reopen');
    Route::post('/user/actionsystem/reopen', 'ActionController@reopenissue');
    Route::get('/user/actionsystem/reviewstatus', 'ActionController@reviewstatus');
    Route::post('/user/actionsystem/reviewed_ok', 'ActionController@actionreviewed_ok')->name('user.audit.approved');
    Route::get('/user/actionsystem/reviewed_notok', 'ActionController@notok_remarks');
    Route::post('user/actionsystem/reviewed_notok', 'ActionController@notok_addremarks');
    Route::get('/user/actionsystem/closure', 'ActionController@closure');
    Route::post('/user/actionsystem/closurereview', 'ActionController@closurereview')->name('user.actionsystem.closurereview');
    Route::get('/user/actionsystem/changes', 'ActionController@changes');
    Route::post('user/actionsystem/suggestchanges', 'ActionController@suggestchanges')->name('actionsystem.suggestchanges');
    //Route::get('/user/actionsystem/closed', 'ActionController@closed');
    Route::get('/user/actionsystem/addissue', 'ActionController@addissue');
    Route::post('/user/actionsystem/insert', 'ActionController@insertissue');
    Route::get('/user/actionsystem/resubmit', 'ActionController@resubmit');
    Route::post('/user/actionsystem/resubmit', 'ActionController@resubmitissue');
    Route::get('/user/actionsystem/openissue', 'ActionController@openissue');
    Route::get('/user/actionsystem/editopen', 'ActionController@editopen');
    Route::get('/user/actionsystem/{id}/detailopen', 'ActionController@detailopen')->name('user.actionsystem.detailopen');
    Route::get('/user/actionsystem/{id}/detailopen-pdf', 'ActionController@exportPDFdetailopen')->name('user.actionsystem.exportPDF.detailopen');
    Route::post('user/actionsystem/displayrootcorrectiveajax', 'ActionController@displayrootcorrectiveajax')->name('audit.displayrootcorrectiveajax');
    Route::post('/user/actionsystem/updateopen', 'ActionController@updateopen')->name('user.actionsystem.updateopenissue');
    Route::get('/user/actionsystem/editrespondedissue', 'ActionController@editrespondedissue');
    Route::post('/user/actionsystem/updaterespondedissue', 'ActionController@updaterespondedissue')->name('user.actionsystem.updaterespondedissue');
    Route::get('/user/actionsystem/closedissue', 'ActionController@closedissue');
    Route::get('/user/actionsystem/analysis', 'ActionController@analysis');
    Route::any('user/actionsystem/search', 'ActionController@search');
    Route::any('user/actionsystem/raised_search', 'ActionController@raised_search');
    Route::any('user/actionsystem/open_search', 'ActionController@open_search');
    Route::any('user/actionsystem/closed_search', 'ActionController@closed_search');
    Route::get('/user/actionsystem/hundred', 'ActionController@hundred');
    Route::get('/user/actionsystem/five', 'ActionController@five');
    Route::get('/user/actionsystem/two', 'ActionController@two');
    Route::get('/user/actionsystem/zero', 'ActionController@zero');
    Route::get('user/actionsystem/delete', 'ActionController@delete')->name('deleteaction');
    Route::get('user/actionsystem/editreq', 'ActionController@editreq')->name('editaction');
    Route::post('/user/actionsystem/editrequest', 'ActionController@editrequest');
    Route::get('/user/actionsystem/addauditobservation', 'ActionController@addauditobservation');
    Route::post('/user/actionsystem/insertobservations', 'ActionController@insertobservations');
    Route::get('/user/actionsystem/{id}/hod-review-form', 'ActionController@showHodReviewForm')->name('user.actionsystem.hodreviewform');
    Route::post('/user/actionsystem/{id}/hod-review-submit', 'ActionController@submitHodReview')->name('user.actionsystem.hodreviewsubmit');
    //edit responsibility
    Route::get('/user/actionsystem/editresp', 'ActionController@editresp');
    Route::post('/user/actionsystem/submitresp', 'ActionController@submittresp');
    Route::get('/user/internalaudit/summary', 'ActionController@summary');
    //export detail page report in custimized template
    Route::get('/user/actionsystem/export-audit-template/{id}','ActionController@exportauditreporttemplate')->name('user.actionsystem.exportreporttemplate.detailopen');
    //------------register table columns activation starts(making table dynamic)-----------//
    Route::get('/user/audit/register-table/active/{id}', 'ActionController@registertablecolumnactive')->name('audit.register.table.column.activation');
    Route::get('/user/audit/register-table/defaultview', 'ActionController@registertablecolumndefaultview')->name('audit.register.table.column.defaultview');
    Route::post('/user/audit/register-table/removecolumn','ActionController@removecolumn')->name('audit.register.table.column.removecolumn');
    Route::post('/user/audit/register-table/update-position','ActionController@updatePosition')->name('audit.register.table.column.updateposition');
    //------------ends register table columns activation-----------//
    //------------closed page register table columns activation starts(making table dynamic)-----------//
    Route::get('/user/audit/closed/register-table/active/{id}', 'ActionController@closedregistertablecolumnactive')->name('audit.closed.register.table.column.activation');
    Route::get('/user/audit/closed/register-table/defaultview', 'ActionController@closedregistertablecolumndefaultview')->name('audit.closed.register.table.column.defaultview');
    Route::post('/user/audit/closed/register-table/removecolumn','ActionController@closedremovecolumn')->name('audit.closed.register.table.column.removecolumn');
    Route::post('/user/audit/closed/register-table/update-position','ActionController@closedupdatePosition')->name('audit.closed.register.table.column.updateposition');
    //------------ends closed page register table columns activation-----------//

    // CORRECTIVE ACTION

    //List of issues
    // Route::get('/user/corrective/corrissues/{type?}', 'CorrectiveController@viewlist')->name('corrective.viewlist');
    Route::any('/user/corrective/ncr-ca-register/{type?}', 'CorrectiveController@viewlist');
    Route::get('/user/corrective/corrissues/{id}/show', 'CorrectiveController@show')->name('corrective.show');
    Route::get('/user/corrective/corrissues/{id}/investigation', 'CorrectiveController@displayInvestigation')->name('corrective.investigation');
    Route::get('/user/corrective/corrissues/{id}/investigation-report', 'CorrectiveController@displayInvestigationReport');
    Route::get('/user/corrective/corrissues/{id}/investigation-team-modification', 'CorrectiveController@changeInvestigationTeam')->name('corrective.change.investigation.team');
    Route::get('/user/corrective/corrissues/{id}/detail', 'CorrectiveController@detail')->name('corrective.detail');
    Route::get('/user/corrective/corrissues/{id}/detail-pdf', 'CorrectiveController@exportPdf')->name('corrective.exportPdf');
    Route::get('/user/corrective/corrissues/{type?}', 'CorrectiveController@ncrCARegister')->name('corrective.viewlist');
    Route::any('/user/corrective/view', 'CorrectiveController@view')->name('user.view');
    //Export functionality of register page
    Route::get('/user/corrective/export-data/{format}/{tab?}', 'CorrectiveController@exportData')->name('corrective.exportData');
    //add corrective
    Route::get('/user/corrective/addcorrective', 'CorrectiveController@addcorrective');
    Route::post('/user/corrective/insert', 'CorrectiveController@insertissue');
    //Initial Review
    Route::get('/user/corrective/approve', 'CorrectiveController@approve_');
    Route::post('/user/corrective/approvement', 'CorrectiveController@approvement');
    Route::get('/user/corrective/disapprovereason/{id}', 'CorrectiveController@disapprovereason');
    Route::post('/user/corrective/disapprove', 'CorrectiveController@disapprove')->name('user.corrective.disapprove');

    //Raised issue
    Route::get('/user/corrective/raisedissue', 'CorrectiveController@raisedissue');
    Route::get('/user/corrective/editraised', 'CorrectiveController@editraised');
    Route::post('/user/corrective/updateraised', 'CorrectiveController@updateraised')->name('user.corrective.updateraised');
    Route::get('/user/corrective/removeraised', 'CorrectiveController@removeraised');
    //Resubmit
    Route::get('/user/corrective/resubmit', 'CorrectiveController@resubmit');
    Route::post('/user/corrective/resubmit', 'CorrectiveController@resubmitissue');

    //Reopen
    Route::get('/user/corrective/reopen/{id}', 'CorrectiveController@reopen')->name('user.corrective.reopen');
    Route::post('/user/corrective/reopen', 'CorrectiveController@reopenissue')->name('user.corrective.post_reopen');

    //Submit investigation form
    Route::post('/user/corrective/submit-investigation', 'CorrectiveController@submitInvestigationForm')->name('user.corrective.submit_investigation_form');

    //Submit change investigation form
    Route::post('/user/corrective/submit-change-investigation', 'CorrectiveController@submitChangeInvestigationForm')->name('user.corrective.submit_change_investigation_form');
    //Delete attachments
    Route::post('/user/corrective/{id}/delete-issue','CorrectiveController@deleteIssueAttachment');
    Route::post('/user/corrective/{id}/delete-action','CorrectiveController@deleteActionAttachment');

    //My open issue
    Route::get('/user/corrective/openissue', 'CorrectiveController@openissue');
    //------------register table columns activation starts(making table dynamic)-----------//
    Route::get('/user/corrective/register-table/active/{id}', 'CorrectiveController@registertablecolumnactive')->name('corrective.register.table.column.activation');
    Route::get('/user/corrective/register-table/defaultview', 'CorrectiveController@registertablecolumndefaultview')->name('corrective.register.table.column.defaultview');
    Route::post('/user/corrective/register-table/removecolumn','CorrectiveController@removecolumn')->name('corrective.register.table.column.removecolumn');
    Route::post('/user/corrective/register-table/update-position','CorrectiveController@updatePosition')->name('corrective.register.table.column.updateposition');
    //------------ends register table columns activation-----------//
    //------------my-open ca table columns activation starts(making table dynamic)-----------//
    Route::get('/user/corrective/myopen-table/active/{id}', 'CorrectiveController@myopentablecolumnactive')->name('corrective.myopen.table.column.activation');
    Route::get('/user/corrective/myopen-table/defaultview', 'CorrectiveController@myopentablecolumndefaultview')->name('corrective.myopen.table.column.defaultview');
    Route::post('/user/corrective/myopen-table/removecolumn','CorrectiveController@removecolumnmyopen')->name('corrective.myopen.table.column.removecolumn');
    Route::post('/user/corrective/myopen-table/update-position','CorrectiveController@updatePositionMyOpen')->name('corrective.myopen.table.column.updateposition');
    //------------ends register table columns activation-----------//
    Route::get('/user/corrective/proposeaction', 'CorrectiveController@editopen');
    Route::post('user/corrective/displayrootcorrectiveajax', 'CorrectiveController@displayrootcorrectiveajax')->name('corrective.displayrootcorrectiveajax');
    Route::post('/user/corrective/updateopen', 'CorrectiveController@updateopen')->name('user.corrective.updateopenissue');
    Route::get('/user/corrective/editrespondedissue', 'CorrectiveController@editrespondedissue');
    Route::post('/user/corrective/updaterespondedissue', 'CorrectiveController@updaterespondedissue')->name('user.corrective.updaterespondedissue');
    Route::get('/user/corrective/edit/{id}', 'CorrectiveController@edit')->name('user.corrective.edit');
    Route::post('/user/corrective/update', 'CorrectiveController@update')->name('user.corrective.update');
    Route::get('/user/corrective/edit-proposed/{id}', 'CorrectiveController@editProposed')->name('user.corrective.edit_proposed');
    Route::post('/user/corrective/update-proposed', 'CorrectiveController@updateProposed')->name('user.corrective.update_proposed');

    //My closed issue
    Route::get('/user/corrective/closedissue', 'CorrectiveController@closedissue');
    //------------register table columns activation starts(making table dynamic)-----------//
    Route::get('/user/corrective/closed-table/active/{id}', 'CorrectiveController@closedtablecolumnactive')->name('corrective.closed.table.column.activation');
    Route::get('/user/corrective/closed-table/defaultview', 'CorrectiveController@closedtablecolumndefaultview')->name('corrective.closed.table.column.defaultview');
    Route::post('/user/corrective/closed-table/removecolumn','CorrectiveController@removecolumnclosed')->name('corrective.closed.table.column.removecolumn');
    Route::post('/user/corrective/closed-table/update-position','CorrectiveController@updatePositionClosed')->name('corrective.closed.table.column.updateposition');
    //------------ends register table columns activation-----------//
    // -----------------------Category filter in register page-------------------------//
     //------------register table columns activation starts(making table dynamic)-----------//
    Route::get('/user/corrective/category-table/active/{id}', 'CorrectiveController@categorytablecolumnactive')->name('corrective.category.table.column.activation');
    // Route::get('/user/corrective/register-table/defaultview', 'CorrectiveController@registertablecolumndefaultview')->name('corrective.register.table.column.defaultview');
    Route::post('/user/corrective/category-table/removecolumn','CorrectiveController@categoryremovecolumn')->name('corrective.category.table.column.removecolumn');
    // Route::post('/user/corrective/register-table/update-position','CorrectiveController@updatePosition')->name('corrective.register.table.column.updateposition');
    Route::get('/user/corrective/category-table/clear-filter', 'CorrectiveController@clearFilter')->name('corrective.category.clear.filter');
    Route::get('/user/corrective/category-table/last-selected-category', 'CorrectiveController@lastSelectedCategory')->name('corrective.category.last.selected');
    // ----------------------------------Ends Category filter in register page-------------------------//
    //Secondary review(Review Status)
    Route::get('/user/corrective/reviewstatus', 'CorrectiveController@reviewstatus');
    Route::post('/user/corrective/reviewed_ok', 'CorrectiveController@actionreviewed_ok')->name('user.corrective.approved');
    Route::get('/user/corrective/update_percentage/{id}', 'CorrectiveController@updatePercentage')->name('user.corrective.update_percentage');
    Route::post('/user/corrective/post_update_percentage', 'CorrectiveController@postUpdatePercentage')->name('user.corrective.post_update_percentage');
    Route::get('/user/corrective/reviewed_notok', 'CorrectiveController@notok_remarks');
    Route::post('user/corrective/reviewed_notok', 'CorrectiveController@notok_addremarks');
    //Closure review
    Route::get('/user/corrective/closure', 'CorrectiveController@closure');
    Route::get('/user/corrective/changes/{id}/{status?}', 'CorrectiveController@changes')->name('user.corrective.changes');
    Route::post('user/corrective/suggestchanges', 'CorrectiveController@suggestchanges')->name('corrective.suggestchanges');
    Route::post('/user/corrective/closed/{id}', 'CorrectiveController@closed')->name('user.corrective.closed');
    //search
    Route::any('user/corrective/search', 'CorrectiveController@search');
    Route::any('user/corrective/raised_search', 'CorrectiveController@raised_search');
    Route::any('user/corrective/open_search', 'CorrectiveController@open_search');
    Route::any('user/corrective/closed_search', 'CorrectiveController@closed_search');
    // //Analysis
    Route::get('/user/corrective/analysis', 'CorrectiveController@analysis');
    //delete
    Route::get('user/corrective/delete', 'CorrectiveController@delete')->name('deletecorr');
    //edit responsibility
    Route::get('/user/corrective/editresp', 'CorrectiveController@editresp');
    Route::post('/user/corrective/submitresp', 'CorrectiveController@submittresp');

    Route::get('/user/corrective/ajax-enable-source/{id}','CorrectiveController@enableSource')->name('user.corrective.enableSource');
    Route::get('/user/corrective/get-product-ncr-fields/{category}/{mode?}/{id?}', 'CorrectiveController@getProductNcrFields');

    //Get title and description for corrective action for previous ncr
    Route::get('/user/corrective/get-previous-ncr-details','CorrectiveController@getPreviousNcrDetails');

    //Compliance
    Route::get('/user/compliance/home', 'ComplianceController@index')->name('compliancedashboard');
    Route::get('/user/compliance/getAttachment/{id}', 'ComplianceController@getAttachment')->name('compliance.getAttachment');

    //compliance_add
    Route::get('/user/compliance/add', 'ComplianceController@add');
    Route::post('/user/compliance/submit', 'ComplianceController@Insert');
    Route::get('/user/compliance/get-legal-documents/{authority_id}', 'ComplianceController@getLegalDocuments');
    Route::get('/user/compliance/check-legal-document', 'ComplianceController@checkLegalDocument');
    Route::get('/user/compliance/get-regulatory','ComplianceController@getRegulatory');
    Route::get('/user/compliance/check-regulatory','ComplianceController@checkRegulatory');
    //compliance_myactions
    //Route::get('/user/compliance/myactions', 'ComplianceController@myactions');
    Route::post('user/compliance/reject', 'ComplianceController@reject')->name('user.compliance.reject');
    //Route::post('user/compliance/reverting', 'ComplianceController@reverting')->name('reverting');
    Route::post('user/compliance/approve', 'ComplianceController@approve')->name('user.compliance.approve');
    //Route::post('user/compliance/approve', 'ComplianceController@addapprove');
    Route::get('user/compliance/entryreview/{id}','ComplianceController@entryreview')->name('user.compliance.enteryreview');
    Route::post('user/compliance/entryreview', 'ComplianceController@addEntryReview')->name('user.compliance.addenteryreview');
    Route::get('user/compliance/change-responsibility/{id}','ComplianceController@changeResponsibility')->name('user.compliance.change_responsibility');
    Route::post('user/compliance/change-responsibility', 'ComplianceController@postChangeResponsibility')->name('user.compliance.post_change_responsibility');
    Route::post('user/compliance/change-legal-coordinator', 'ComplianceController@postChangeCoordinator')->name('user.compliance.post_change_coordinator');
    Route::get('user/compliance/send-add-compliance-emails', 'ComplianceController@sendReviewRequestMail')->name('user.compliance.send_add_compliance_emails');

    //compliance_raised
    //Route::get('/user/compliance/raised', 'ComplianceController@raised');

    //compliance_register
    Route::get('/user/compliancemgmt/{type?}', 'ComplianceController@regcompliance')->name('compliance.register');
    //Route::any('/user/compliance/filter', 'ComplianceController@view');
    Route::get('/user/compliance/hide', 'ComplianceController@hide');
    Route::get('/user/compliance/unhide', 'ComplianceController@unhide');
    Route::get('/user/compliance/choosevisibility', 'ComplianceController@choosevisibility');
    Route::post('/user/compliance/visible', 'ComplianceController@visible')->name('visible');
    Route::get('/user/compliance/edit', 'ComplianceController@addsedit');
    Route::post('/user/compliance/editcompliance', 'ComplianceController@editcompliance');
    // Route::get('/user/compliance/{owner} {Cid}', ['as' =>'complied','uses' => 'ComplianceController@complied']);
    Route::get('user/compliance/complied', 'ComplianceController@complied');
    Route::get('user/compliance/notcomply', 'ComplianceController@notcomply');
    Route::get('user/compliance/partiallycomply', 'ComplianceController@partiallycomply');
    Route::get('user/compliance/underreview', 'ComplianceController@underreview');
    Route::get('/user/compliance/review', 'ComplianceController@review');
    Route::post('user/compliance/review', 'ComplianceController@addreview')->name('user.compliance.addreview');
    //compliance_analysis
    Route::get('/user/compliance/companalysis', 'ComplianceController@analysis');
    //Route::any('user/compliance/search', 'ComplianceController@search');
    // Route::any('user/compliance/myactions_search', 'ComplianceController@myactions_search');
    //Reopen
    Route::get('/user/compliance/reopen', 'ComplianceController@reopen');
    Route::post('/user/compliance/reopen', 'ComplianceController@reopenlegal');
    //Delete
    // Route::get('/user/compliance/deletecomp','ComplianceController@deletecomp');
    // Route::post('/user/compliance/deletecomp','ComplianceController@deleteconf');
    //delete
    Route::get('user/compliance/delete', 'ComplianceController@delete')->name('deletecompliance');
    //edit request
    Route::get('user/compliance/editreq', 'ComplianceController@editreq')->name('editcompliance');
    Route::post('/user/compliance/editrequest', 'ComplianceController@editrequest');
    Route::get('user/compliance/reviewcompliancestatus/{id}', 'ComplianceController@reviewComplianceStatus')->name('user.compliance.review_status');
    Route::post('user/compliance/reviewcompliancestatus', 'ComplianceController@addReviewComplianceStatus')->name('user.compliance.add_review_status');
    Route::get('user/compliance/resubmit/{id}','ComplianceController@resubmit')->name('user.compliance.resubmit');
    Route::post('user/compliance/resubmit', 'ComplianceController@addResubmit')->name('user.compliance.add_resubmit');
    Route::post('user/compliance/addLicensePermit','ComplianceController@addLicensePermit')->name('user.compliance.addlicensepermit');
    Route::get('user/compliance/licensepermits/{type?}','ComplianceController@viewLicensePermits')->name('user.compliance.viewlicensepermit');
    Route::post('user/compliance/deletelicense','ComplianceController@deleteLicensePermits')->name('user.compliance.deletelicensepermit');
    Route::get('user/compliance/editlicensepermit/{id}','ComplianceController@editLicensePermit')->name('user.compliance.editlicensepermit');
    Route::post('user/compliance/updatelicensepermit','ComplianceController@updateLicensePermit')->name('user.compliance.updatelicensepermit');
    Route::get('user/compliance/renewlicensepermit/{id}','ComplianceController@renewLicensePermit')->name('user.compliance.renewlicensepermit');
    Route::post('user/compliance/renewedlicensepermit','ComplianceController@renewedLicensePermit')->name('user.compliance.renewedlicensepermit');
    //Periodical Review
    //Route::get('user/compliance/periodicalreview/{id}', 'ComplianceController@showPeriodicalReview');
    //Route::post('/user/compliance/periodicalreview', 'ComplianceController@periodicalReview');
    Route::get('user/compliance/detail/{id}', 'ComplianceController@detail')->name('user.compliance.detail');
    Route::get('user/compliance/detail-pdf/{id}', 'ComplianceController@detailPdf')->name('user.compliance.detail.pdf');

    //OHS Risk
    Route::get('/user/ohsrisk/add/{type?}', 'OhsRiskController@add')->name('addohsrisk');
    Route::post('/user/ohsrisk/submit', 'OhsRiskController@insert');

    Route::get('/user/ohsrisk/list/{department}/{area}/{type?}', 'OhsRiskController@list');
    Route::get('/user/ohsrisk/list2/{location}', 'OhsRiskController@list2');
    Route::any('user/ohsrisk/filtr', 'OhsRiskController@filtr');
    Route::get('/user/ohsrisk/masterlist/{type?}', 'OhsRiskController@masterlist');
    Route::any('/user/ohsrisk/masterfiltr', 'OhsRiskController@masterfiltr');
    Route::get('/user/ohsrisk/addmore/{type?}', 'OhsRiskController@addmore');
    Route::post('user/ohsrisk/submitmore', 'OhsRiskController@insertmore');
    Route::get('/user/ohsrisk/review/{type?}', 'OhsRiskController@adds');
    Route::post('/user/ohsrisk/initialreview', 'OhsRiskController@updateinitial');
    //Route::get('/user/ohsrisk/propose', 'OhsRiskController@propose');
    //Route::post('user/ohsrisk/propose_change', 'OhsRiskController@propose_change')->name('propose_change');
    Route::get('/user/ohsrisk/resubmit/{pagetype?}', 'OhsRiskController@resubmit');
    Route::post('/user/ohsrisk/resubmit', 'OhsRiskController@updateresubmit');
    Route::get('/user/ohsrisk/secondaryreview/{type?}', 'OhsRiskController@addsecondary');
    Route::post('/user/ohsrisk/secondaryreview', 'OhsRiskController@updatesecondary');
    Route::get('/user/ohsrisk/action/{type?}', 'OhsRiskController@action');
    Route::post('user/ohsrisk/control_pro', 'OhsRiskController@control_pro')->name('control_pro');
    //Route::get('/user/ohsrisk/info', 'OhsRiskController@info');
    //Route::any('user/ohsrisk/search', 'OhsRiskController@search');
    //Route::any('user/ohsrisk/mastersearch', 'OhsRiskController@mastersearch');
    //Route::get('/user/ohsrisk/deleterisk', 'OhsRiskController@deleterisk');
    //Route::post('/user/ohsrisk/deleterisk/confirm/', 'OhsRiskController@deleteriskconf')->name('risk.delete');
    Route::get('/user/ohsrisk/deleteactivity', 'OhsRiskController@deleteactivity')->name('delete.activity');
    Route::get('/user/ohsrisk/analysis', 'OhsRiskController@analysis');
    Route::get('user/ohsrisk/deleteohs', 'OhsRiskController@deleteohs')->name('deleteohs');
    Route::get('user/ohsrisk/editreq_ohs/{type?}', 'OhsRiskController@editreq_ohs')->name('editohs');
    Route::post('/user/ohsrisk/editrequest_ohs', 'OhsRiskController@editrequest_ohs');
    Route::get('/user/ohsrisk/folder/{type?}', 'OhsRiskController@folder')->name('ohsrisk.folder');
    //Route::post('/user/ohsrisk/createfolder', 'OhsRiskController@createfolder');
    Route::get('user/ohsrisk/subfolder/{dept}/{type?}', 'OhsRiskController@subfolder');
    Route::get('user/ohsrisk/divisionsubfolder/{dept}/{type?}', 'OhsRiskController@divisionSubFolder');
    Route::get('user/ohsrisk/divsionsubdivisionfolder/{id}/{dept}/{type?}', 'OhsriskController@subDivisionSubFolder');

    //Document management Routes

    Route::get('/user/docmgmt', 'DocumentController@view')->name('doc.home');
    Route::group(['prefix' => '/user/doc', 'as' => 'doc.'], function () {
        //Route::get('/test', 'DocumentController@test')->name('test');
        //Route::get('/laravel-timestamps', 'DocumentController@addTimestampsInMasterdocs');
        //Route::get('/dcr-change', 'DocumentController@dcrChange');
        //Route::get('/document-change', 'DocumentController@doumentChange');
        //Route::get('/dochistory-change', 'DocumentController@dochistoryChange');
        //Route::get('/home', 'DocumentController@view')->name('home');
        Route::get('/add', 'DocumentController@add')->name('add');
        //adding dcr
        Route::post('/adddcr', 'DocumentController@adddcr')->name('adddcr');
        //open
        Route::get('/open/{id}', 'DocumentController@open')->name('open');
        Route::post('/approve', 'DocumentController@approve')->name('approve');
        Route::get('/disapprove/{id}', 'DocumentController@disapprove')->name('disapprove');
        Route::get('/ok/{id}', 'DocumentController@ok')->name('ok');
        Route::get('/notok/{id}', 'DocumentController@notok')->name('notok');
        //explorer
        Route::get('/explorer', 'DocumentController@explorer')->name('explorer');
        // //review
        //approve function for  doc controller not hod review,its just name changed
        Route::get('/hodreview/{id}', 'DocumentController@addss')->name('addss');
        Route::post('/hodreview', 'DocumentController@hodreview')->name('hodreview');
        //dar register
        Route::get('/register', 'DocumentController@register')->name('register');
        //delete
        Route::get('/dardelete/{id}', 'DocumentController@dardelete')->name('dardelete');
        //Route::get('/daredit/{id}', 'DocumentController@daredit')->name('daredit');
        //history
        Route::get('/history', 'DocumentController@history')->name('history');
        Route::get('/addmaster', 'DocumentController@addmaster')->name('addmaster');
        //adding master doc
        Route::post('/addmasterlist', 'DocumentController@addmasterlist')->name('addmasterlist');
        //change request
        Route::get('/changereq/{id}', 'DocumentController@changereq')->name('change');
        Route::post('/changerequest', 'DocumentController@changerequest')->name('change_post');
        Route::get('/editchangerequest/{id}', 'DocumentController@editchangerequest')->name('editchangerequest');
        Route::post('/submitchangerequest', 'DocumentController@updatechangerequest')->name('updatechangerequest');
        Route::get('/deletechangerequest/{id}', 'DocumentController@deletechangerequest')->name('deletechangerequest');
        //remove request
        Route::get('/removereq/{id}', 'DocumentController@removereq')->name('remove');
        Route::post('/removalrequest', 'DocumentController@removalrequest')->name('removalrequest');
        Route::get('/editremoverequest/{id}', 'DocumentController@editremoverequest')->name('editremoverequest');
        Route::post('/submitremoverequest', 'DocumentController@updateremoverequest')->name('updateremoverequest');
        Route::get('/deleteremoverequest/{id}', 'DocumentController@deleteremoverequest')->name('deleteremoverequest');
        //delete request
        Route::get('/deletereq/{id}', 'DocumentController@deletereq')->name('delete');
        //edit
        Route::get('/editreq/{id}', 'DocumentController@editreq')->name('editdoc');
        Route::post('/editrequest', 'DocumentController@editrequest')->name('editrequest');
        //hod
        Route::get('/creview/{id}', 'DocumentController@creview')->name('creview');
        Route::get('/changeok/{id}', 'DocumentController@changeok')->name('changeok');
        Route::get('/changenotok/{id}', 'DocumentController@changenotok')->name('changenotok');
        //master review
        Route::get('/masterreview/{id}', 'DocumentController@masterreview')->name('masterreview');
        Route::post('/approvemaster', 'DocumentController@approvemaster')->name('approvemaster');
        Route::get('/disapprovemaster/{id}', 'DocumentController@disapprovemaster')->name('disapprovemaster');

        //add request
        //resubmit
        Route::get('/resubmit/{id}', 'DocumentController@resubmit')->name('resubmit');
        Route::post('/resubmit', 'DocumentController@resubmitdoc')->name('resubmitdoc');
        Route::get('/deleteaddrequest/{id}', 'DocumentController@deleteaddrequest')->name('deleteaddrequest');
        //hod
        Route::get('/rreview/{id}', 'DocumentController@rreview')->name('rreview');
        Route::get('/removeok/{id}', 'DocumentController@removeok')->name('removeok');
        Route::get('/removenotok/{id}', 'DocumentController@removenotok')->name('removenotok');
        Route::get('/removereview/{id}', 'DocumentController@removereview')->name('removereview');
        Route::get('/approveremove/{id}', 'DocumentController@approveremove')->name('approveremove');
        Route::get('/disapproveremove/{id}', 'DocumentController@disapproveremove')->name('disapproveremove');
        Route::get('/analysis', 'DocumentController@analysis')->name('analysis');

        //Governance Hub
        Route::get('/governancehub', 'DocumentController@governancehub')->name('governancehub');
        Route::get('/procedures/{id?}', 'DocumentController@procedures')->name('procedures');
        Route::post('/retrieve-document-number', 'DocumentController@retrieveDocumentNumber')->name('retrieveDocumentNumber');
        Route::post('/document-number-check', 'DocumentController@checkDocumentNumber')->name('checkDocumentNumber');
        Route::post('/save-document', 'DocumentController@saveDocument')->name('saveDocument');
        Route::post('/save-document-draft', 'DocumentController@saveDocumentDraft')->name('saveDocumentDraft');
        Route::post('/update-views/{id}', 'DocumentController@updateViews')->name('updateViews');
        Route::get('add-content/{id}', 'DocumentController@addContent')->name('add_content');
        Route::post('insert-content', 'DocumentController@insertContent')->name('insert_content');
        Route::get('documentuploadcontent/{id}', 'DocumentController@documentuploadcontent')->name('documentuploadcontent');
        Route::post('insertdocumentexcelcontent', 'DocumentController@insertdocumentexcelcontent')->name('insertdocumentexcelcontent');
        Route::post('/update-content-text', 'DocumentController@updateContentText')->name('update_content_text');
        Route::post('/delete-content', 'DocumentController@deleteContent')->name('delete_content');
        Route::post('/trash-document','DocumentController@trashdocument')->name('trashdocument');
        Route::get('/trash-document-list','DocumentController@trashdocumentlist')->name('trashdocumentlist');
        Route::post('/restore-document','DocumentController@restoredocument')->name('restoredocument');
        Route::post('/delete-document','DocumentController@deletedocument')->name('deletedocument');
        Route::get('/edit-document/{id}','DocumentController@editdocument')->name('edit_document');
        Route::post('/edit-document-request','DocumentController@editdocumentrequest')->name('edit_document_request');
        Route::post('/review', 'DocumentController@addReview')->name('review');
        //-------change request governancehub----------//
        Route::post('/governancehubchangerequests','DocumentController@governancehubchangerequest')->name('document.governancehub.changerequest');
        //-------read request governancehub----------//
        Route::post('/governancehubreaderequest','DocumentController@governancehubreaderequest')->name('document.governancehub.readrequest');
        //------------------procedures export-----------------------//
        Route::get('/exportdocumentreportpdf/{id}', 'DocumentController@exportdocumentreportpdf')->name('exportdocumentreportpdf');
        Route::get('/check-pdf-download-status', 'DocumentController@checkPdfDownloadStatus')->name('checkPdfDownloadStatus');
        //----------------------procedure search------------------------------------//
        Route::get('/search/procedures/{id}', 'DocumentController@searchprocedures')->name('searchprocedures');
        //----------------------procedure reference------------------------------------//
        Route::post('/governancehubdocumentreference', 'DocumentController@storedocumentreference')->name('storedocumentreference');
        //----------------------procedure definition------------------------------------//
        Route::post('/governancehubdocumentdefinition', 'DocumentController@storedocumentdefinition')->name('storedocumentdefinition');
        //--------------------------comment notification-------------------------------------------------//
        Route::post('/updateusercommentnotification', 'DocumentController@updateusercommentnotification')->name('updateusercommentnotification');
        //Draft content
        Route::get('document-draft', 'DocumentController@documentDraft')->name('document_draft');
        Route::get('/procedures-draft/{id}','DocumentController@proceduresDraft')->name('procedures_draft');
        Route::post('/get-cached-base64-image', 'DocumentController@getCachedBase64Image')->name('get_cached_base64_image');
        Route::post('/process-base64-image', 'DocumentController@processBase64Image')->name('process_base64_image');
        Route::post('/get-content-for-edit', 'DocumentController@getContentForEdit')->name('get_content_for_edit');
        Route::post('/load-edit-modal', 'DocumentController@loadEditModal')->name('load_edit_modal');
        Route::post('/update-content-position/{id?}', 'DocumentController@updateContentPosition')->name('update.content.position');
        Route::get('/add-content-draft/{id}', 'DocumentController@addContentDraft')->name('add_content_draft');
        Route::post('insert-content-draft', 'DocumentController@insertContentDraft')->name('insert_content_draft');
        Route::post('/update-draft-content-text', 'DocumentController@updateDraftContentText')->name('update_draft_content_text');
        Route::post('/delete-draft-content', 'DocumentController@deleteDraftContent')->name('delete_draft_content');
        Route::get('draftuploadcontent/{id}', 'DocumentController@draftuploadcontent')->name('draftuploadcontent');
        Route::post('insertdraftexcelcontent', 'DocumentController@insertdraftexcelcontent')->name('insertdraftexcelcontent');
        //---------------select draft reviewers and approvers----------------------//
        Route::post('/selectdraftreviewers','DocumentController@selectdraftreviewers')->name('selectdraftreviewers');
        Route::post('/insert-reviewer-approver', 'DocumentController@storereviewerapprover')->name('storereviewerapprover');
        //---------------End select draft reviewers and approvers----------------------//

        //---------------modify draft reviewers----------------------//
        Route::post('/modifydraftreviewers','DocumentController@modifydraftreviewers')->name('modifydraftreviewers');
        Route::post('/updatemodifieddraftreviewer', 'DocumentController@updatemodifieddraftreviewer')->name('updatemodifieddraftreviewer');
        //---------------End modify draft reviewers----------------------//

        //---------------modify draft approvers----------------------//
        Route::post('/modifydraftapprovers','DocumentController@modifydraftapprovers')->name('modifydraftapprovers');
        Route::post('/updatemodifieddraftapprover', 'DocumentController@updatemodifieddraftapprover')->name('updatemodifieddraftapprover');
        //---------------End modify draft approvers----------------------//

    //---------------draft review starts----------------------//
        Route::get('/review-draft/{id}', 'DocumentController@reviewDraft')->name('review_draft');
        Route::get('/review-reject-draft/{id}','DocumentController@reviewRejectDraft')->name('reviewrejectdraft');
        Route::post('/notok-addremarks-review','DocumentController@notok_addremarks')->name('insert_review_rejection_reason');
        Route::get('/review-accept-draft/{id}','DocumentController@reviewAcceptDraft')->name('reviewacceptdraft');
        Route::post('/review-accept-remarks-draft','DocumentController@reviewacceptremarksdraft')->name('reviewacceptremarksdraft');
        //---------------End draft review ----------------------//

        //---------------draft approve----------------------//
        Route::get('/approve-draft/{id}', 'DocumentController@approveDraftdetails')->name('approve_draft_details');
        Route::get('/approve-reject-draft/{id}','DocumentController@approveRejectDraft')->name('approverejectdraft');
        Route::post('/notok-addremarks','DocumentController@notok_approve')->name('insert_approval_reject_reason');
        Route::get('/approved-document-draft/{id}','DocumentController@approveDraft')->name('approvedraft');
        Route::post('/approve-accept-remarks-draft','DocumentController@approveacceptremarksdraft')->name('approveacceptremarksdraft');
        //---------------End draft approve----------------------//

        //---------------release draft document----------------------//
        Route::get('/releasechangerequestdraft/{id}','DocumentController@releasechangerequestdraft')->name('releasechangerequestdraft');
        Route::post('/releasenewdraftdocument','DocumentController@releasenewdraftdocument')->name('releasenewdraftdocument');
        //---------------End release draft document----------------------//
        //---------------Cancel change request----------------------//
        Route::get('/cancelchangerequesthub/{id}','DocumentController@cancelchangerequesthub')->name('cancelchangerequesthub');
        //---------------End Cancel change request----------------------//
        Route::get('/edit-draft/{id}','DocumentController@editDraft')->name('editdraft');
        Route::post('/edit-draft-content-text', 'DocumentController@editDraftContentText')->name('edit_draft_content_text');
        Route::post('/trash-draft','DocumentController@trashdraft')->name('trashdraft');
        Route::get('/trash-draft-list','DocumentController@trashdraftlist')->name('trashdraftlist');
        Route::post('/restore-draft','DocumentController@restoredraft')->name('restoredraft');
        Route::post('/delete-draft','DocumentController@deletedraft')->name('deletedraft');
        //------------------procedures-draft export-----------------------//
        Route::get('/exportdocumentdraftreportpdf/{id}', 'DocumentController@exportdocumentdraftreportpdf')->name('exportdocumentdraftreportpdf');

    });
    Route::group(['prefix' => '/user/governancehub/comment', 'as' => 'documentcomment.'], function () {
        Route::post('add', 'GovernanceHubCommentController@store')->name('store');
        Route::get('delete/{id}', 'GovernanceHubCommentController@destroy')->name('destroy');
        Route::get('delete-reply/{id}', 'GovernanceHubCommentController@deleteReply')->name('delete_reply');
    });

    //---------------draft comments starts----------------------//
    Route::group(['prefix' => '/user/governancehub/draft/comment', 'as' => 'documentdraftcomment.'], function () {
        Route::post('add', 'GovernanceHubDraftCommentController@store')->name('store');
        Route::get('delete/{id}', 'GovernanceHubDraftCommentController@destroy')->name('destroy');
        Route::get('delete-reply/{id}', 'GovernanceHubDraftCommentController@deleteReply')->name('delete_reply');
    });
    //---------------End draft comments----------------------//

    //internal audit

    //Route::get('/test', 'CorrectiveController@test');

    Route::get('/user/internalaudit/home', 'InternalauditController@index');

    //add audit
    Route::get('/user/internalaudit/addaudit', 'InternalauditController@addaudit');
    Route::get('/user/internalaudit/viewaudit/{id}', 'InternalauditController@viewAudit')->name('user.viewAudit');
    Route::post('/user/internalaudit/selectdeptdivisiondetails', 'InternalauditController@selectdeptdivisiondetails')->name('user.selectdeptdivisiondetails');
    Route::post('/user/internalaudit/selectdivisionareadetails', 'InternalauditController@selectdivisionareadetails')->name('user.selectdivisionareadetails');
    Route::post('/user/internalaudit/selectareachecklistdetails', 'InternalauditController@selectareachecklistdetails')->name('user.selectareachecklistdetails');
    Route::post('/user/internalaudit/submit', 'InternalauditController@insertaudit');
    Route::get('/user/internalaudit/notification', 'InternalauditController@notification');
    // audit schedule
    Route::get('/user/internalaudit/auditschedule/{year}', 'InternalauditController@auditschedule')->name('user.internalaudit.auditschedule');
    Route::get('/user/auditschedule', 'InternalauditController@folder')->name('user.auditschedule');

    Route::any('user/internalaudit/filterschedule', 'InternalauditController@filterschedule');

    Route::get('/user/internalaudit/editschedule', 'InternalauditController@editschedule');
    Route::post('/user/internalaudit/editschedule', 'InternalauditController@updateschedule');

    Route::get('/user/internalaudit/editauditor', 'InternalauditController@editauditor');
    Route::post('/user/internalaudit/editauditor', 'InternalauditController@updateauditor');
    Route::get('/user/internalaudit/deletefile', 'InternalauditController@deletefile');

    Route::get('/user/internalaudit/accept', 'InternalauditController@accept');
    Route::get('/user/internalaudit/propose', 'InternalauditController@propose');

    // Route::get('user/internalaudit/disapprove','RiskController@disapprove');
    Route::post('user/internalaudit/reason', 'InternalauditController@reason')->name('reason');

    Route::get('/user/internalaudit/update', 'InternalauditController@adds');
    Route::post('/user/internalaudit/update', 'InternalauditController@update');

    Route::get('/user/internalaudit/complete', 'InternalauditController@complete');

    Route::get('/user/internalaudit/archive', 'InternalauditController@archive');

    // auditors list
    Route::get('/user/internalaudit/auditorslist', 'InternalauditController@auditorslist')
        ->name('internalaudit.auditorslist');

    Route::get('user/internalaudit/downloading', 'InternalauditController@downloading')->name('downloading');

    // add auditors
    Route::get('/user/internalaudit/addauditors', 'InternalauditController@addauditors');
    Route::post('/user/internalaudit/insertauditors', 'InternalauditController@insertauditors');

    //audit log
    Route::get('/user/internalaudit/auditlog', 'InternalauditController@auditlog');
    //closedaudits
    Route::get('/user/internalaudit/closedaudits', 'InternalauditController@closedaudits');

    Route::get('/user/internalaudit/close', 'InternalauditController@close');

    Route::get('/user/internalaudit/addobservations', 'InternalauditController@addss');
    Route::post('/user/internalaudit/addobservations', 'InternalauditController@addobservations');

    Route::get('/user/internalaudit/closencr', 'InternalauditController@closencr');

    // Route::get('/user/internalaudit/observations','InternalauditController@observations');

    Route::any('user/internalaudit/filtr', 'InternalauditController@filtr');
    Route::get('/user/internalaudit/filtring', 'InternalauditController@filtring');
    // Route::get('/url/internalaudit/observations','InternalauditController@observationview');

    //completedaudits
    Route::get('/user/internalaudit/completedaudits', 'InternalauditController@completedaudits');

    //analysis
    Route::get('/user/internalaudit/analysis', 'InternalauditController@analysis');

    // //filter analysis
    Route::any('/user/internalaudit/analysisfilter', 'InternalauditController@analysisfilter');

    //search
    Route::any('user/internalaudit/search', 'InternalauditController@search');
    //search
    Route::any('user/internalaudit/auditorsearch', 'InternalauditController@auditorsearch');

    Route::any('user/internalaudit/logsearch', 'InternalauditController@logsearch');

    Route::any('user/internalaudit/csearch', 'InternalauditController@csearch');
    // Route::get('/user/internalaudit/document','InternalauditController@document')->name('document');

    //delete
    Route::get('user/internalaudit/deleteaudit', 'InternalauditController@deleteaudit')->name('deleteaudit');
    //delete
    Route::get('user/internalaudit/deleteauditor', 'InternalauditController@deleteauditor')->name('deleteauditor');

    //edit request
    Route::get('user/internalaudit/editreq', 'InternalauditController@editreq')->name('editschedule');
    Route::post('/user/internalaudit/editrequest', 'InternalauditController@editrequest');

    //checklist
    Route::get('user/checklistlibrary', 'InternalauditController@checklistlibrary');
    Route::post('/user/internalaudit/addchecklist', 'InternalauditController@addchecklist')->name('addchecklist');
    Route::post('/user/internalaudit/selectchecklistaddercategory', 'InternalauditController@selectchecklistaddercategory')->name('selectchecklistaddercategory');
    Route::get('/user/internalaudit/mainchecklistapprove/{id}', 'InternalauditController@mainchecklistapprove')->name('mainchecklistapprove');
    Route::post('/user/internalaudit/editchecklistname', 'InternalauditController@editchecklistname')->name('editchecklistname');
    Route::get('/user/internalaudit/deletechecklistname/{id}', 'InternalauditController@deletechecklistname')->name('deletechecklistname');
    Route::post('/user/internalaudit/confirmdeletechecklistname', 'InternalauditController@confirmdeletechecklistname')->name('confirmdeletechecklistname');
    Route::get('/user/internalaudit/approvechecklistremoval/{id}', 'InternalauditController@approvechecklistremoval')->name('approvechecklistremoval');
    Route::get('/user/internalaudit/cancelchecklistremoval/{id}', 'InternalauditController@cancelchecklistremoval')->name('cancelchecklistremoval');
    Route::get('/user/internalaudit/addchecklistsuggestions/{id}', 'InternalauditController@addchecklistsuggestions')->name('addchecklistsuggestions');
    Route::post('/user/internalaudit/storechecklistimprovements', 'InternalauditController@storechecklistimprovements')->name('storechecklistimprovements');
    Route::get('/user/internalaudit/checklistdetails/{id}', 'InternalauditController@checklistdetails');
    Route::post('/user/internalaudit/addchecklisttitle', 'InternalauditController@addchecklisttitle')->name('addchecklisttitle');
    Route::get('/user/internalaudit/editchecklisttitle/{id}', 'InternalauditController@editchecklisttitle')->name('editchecklisttitle');
    Route::post('/user/internalaudit/updatechecklisttitle', 'InternalauditController@updatechecklisttitle')->name('updatechecklisttitle');
    Route::get('/user/internalaudit/deletechecklisttitle/{id}', 'InternalauditController@deletechecklisttitle')->name('deletechecklisttitle');
    Route::post('/user/internalaudit/ajaxaddchecklistpoint', 'InternalauditController@ajaxaddchecklistpoint')->name('ajaxaddchecklistpoint');
    Route::get('/user/internalaudit/addchecklistpoint/{id}', 'InternalauditController@addchecklistpoint')->name('addchecklistpoint');
    Route::post('/user/internalaudit/insertchecklistpoint', 'InternalauditController@insertchecklistpoint')->name('insertchecklistpoint');
    Route::get('user/internalaudit/checklistpurpose/{id}', 'InternalauditController@checklistpointpurpose')->name('checklistpointpurpose');
    Route::post('user/internalaudit/checklistpurpose/submit', 'InternalauditController@insertchecklistpointpurpose')->name('insertchecklistpointpurpose');
    Route::get('user/internalaudit/checklistitemsverified/{id}', 'InternalauditController@checklistitemsverified')->name('checklistpointitemsverified');
    Route::post('user/internalaudit/checklistitemsverified/submit', 'InternalauditController@insertchecklistitemsverified')->name('insertchecklistpointitemsverified');
    Route::get('user/internalaudit/checklistpointsedit/{id}', 'InternalauditController@checklistpointsedit')->name('checklistpointsedit');
    Route::post('user/internalaudit/checklistpointsedit/submit', 'InternalauditController@checklistpointsupdate')->name('checklistpointsupdate');
    Route::get('user/internalaudit/checklistpointsdelete/{id}', 'InternalauditController@checklistpointsdelete')->name('checklistpointsdelete');
    Route::get('user/internalaudit/checklistexcelupload/{id}', 'InternalauditController@checklistexcelupload')->name('checklistexcelupload');
    Route::post('user/internalaudit/insertchecklistexcelupload', 'InternalauditController@insertchecklistexcelupload')->name('insertchecklistexceldatas');
    // Route::get('/user/internalaudit/checklistnewrecord/{id}', 'InternalauditController@checklistnewrecord')->name('checklistnewrecord');
    Route::get('/user/internalaudit/checklistnewrecord/{id}', 'InternalauditController@checklistnewrecordregister')->name('checklistnewrecord');
    Route::get('/user/internalaudit/createchecknewrecord/{id}/{auditid}', 'InternalauditController@createchecknewrecord')->name('createchecknewrecord');
    Route::post('/user/internalaudit/insertchecklistnewrecord', 'InternalauditController@insertchecklistnewrecord')->name('insertchecklistnewrecord');
    Route::post('/user/internalaudit/addchecklistnewrecordattachments', 'InternalauditController@addchecklistnewrecordattachments')->name('addchecklistnewrecordattachments');
    Route::get('/user/internalaudit/deletechecklistnewrecordattachments/{id}', 'InternalauditController@deletechecklistnewrecordattachments')->name('deletechecklistnewrecordattachments');
    Route::get('/user/internalaudit/ajax/deletechecklistnewrecordattachments/{id}', 'InternalauditController@ajaxdeletechecklistnewrecordattachments')->name('ajaxdeletechecklistnewrecordattachments');
    Route::get('/user/internalaudit/checklistnewrecordsdisplay/{id}', 'InternalauditController@checklistnewrecordsdisplay')->name('checklistnewrecordsdisplay');
    Route::get('/user/internalaudit/savechecklistreportpdf', 'InternalauditController@savechecklistreportpdf')->name('savechecklistreportpdf');
    Route::get('/user/internalaudit/checklistnewrecordsedit/{id}', 'InternalauditController@checklistnewrecordsedit')->name('checklistnewrecordsedit');
    Route::post('/user/internalaudit/updatechecklistnewrecords', 'InternalauditController@updatechecklistnewrecords')->name('updatechecklistnewrecords');
    Route::get('/user/internalaudit/deletechecklistnewrecords', 'InternalauditController@deletechecklistnewrecords')->name('deletechecklistnewrecords');
    Route::get('/user/internalaudit/viewdeletedchecklistrecords/{id}/{checklistid}', 'InternalauditController@viewdeletedchecklistrecords')->name('viewdeletedchecklistrecords');
    Route::get('/user/internalaudit/movechecklistnewrecords', 'InternalauditController@movechecklistnewrecords')->name('movechecklistnewrecords');
    Route::get('/user/internalaudit/deletechecklistnewrecordsfr', 'InternalauditController@deletechecklistnewrecordsfr')->name('deletechecklistnewrecordsfr');
    Route::get('/user/internalaudit/linkchecklist/{id}', 'InternalauditController@linkauditchecklist')->name('user.internalaudit.linkchecklist');
    Route::post('/user/internalaudit/linkchecklist/store', 'InternalauditController@storelinkchecklist')->name('user.internalaudit.store.linkchecklist');
    Route::get('/user/internalaudit/linkchecklist/delete/{checklistid}/{auditid}', 'InternalauditController@deletelinkchecklist')->name('user.internalaudit.delete.linkchecklist');
    Route::get('/user/internalaudit/reschedule/{id}', 'InternalauditController@reschedule')->name('user.internalaudit.reschedule');
    Route::post('/user/internalaudit/reschedule/{id}', 'InternalauditController@rescheduleRecord')->name('user.internalaudit.rescheduleRecord');

    /**--user routes for Management Review Module starts--*/
    Route::get('user/management/meetings', 'ManagementReviewController@meetings')->name('management.meetings');
    //add management review schedule
    Route::get('user/management/addmanagementreview', 'ManagementReviewController@addmanagementreview')->name('management.addmanagementreview');
    Route::post('user/management/selectagenda', 'ManagementReviewController@selectagenda')->name('selectagenda');
    Route::post('user/management/addmanagementreview/submit', 'ManagementReviewController@insertmanagementreview')->name('insertmanagementreview');
    Route::post('user/management/selectmodifyagendadata', 'ManagementReviewController@selectmodifyagendadata')->name('selectagendamodifydata');
    Route::post('user/management/updateagendaajax', 'ManagementReviewController@updateagendaajax')->name('updateagendaajax');
    //cancel meeting
    Route::post('user/management/cancelmeetingreason', 'ManagementReviewController@cancelmeetingreason')->name('cancelmeetingreason');
    //record minutes
    Route::get('user/management/recordminutes/{id}', 'ManagementReviewController@recordminutes')->name('management.recordminutes');
    Route::post('user/management/addminutesajax', 'ManagementReviewController@addminutesajax')->name('management.addminutesajax');
    Route::post('user/management/getminutenotesajax', 'ManagementReviewController@getminutenotesajax')->name('management.getminutenotesajax');
    Route::post('user/management/displayminutenotesajax', 'ManagementReviewController@displayminutenotesajax')->name('management.displayminutenotesajax');
    //delete
    Route::get('user/management/deletemanagementreviewmeeting', 'ManagementReviewController@deletemanagementreviewmeeting')->name('management.deletereviewmeeting');
    //action plans
    Route::get('user/management/addcorrective/{id}', 'ManagementReviewController@addcorrective')->name('management.addcorrective');
    Route::post('user/management/insertcorrective', 'ManagementReviewController@insertcorrective')->name('management.insertcorrective');
    //Change Schedule
    Route::post('user/management/updateschedule', 'ManagementReviewController@updateschedule')->name('management.updateschedule');
    //Close
    Route::get('user/management/meetingcompleted/{id}', 'ManagementReviewController@meetingcompleted')->name('management.meetingcompleted');
    //Analysis
    Route::get('user/management/analysis', 'ManagementReviewController@analysis')->name('management.analysis');
    /**--user routes for Management Review Module ends--*/

    /**-------User routes for Emergency Response Module starts-----------*/
    Route::get('user/emergencyresponse/register/{msg?}', 'EmergencyResponseController@register')->name('emergency.register');
    //---------------------Add emergency response----------------------//
    Route::get('user/emergencyresponse/addemergencyresponse', 'EmergencyResponseController@addemergencyresponse')->name('emergency.addemergencyresponse');
    Route::post('user/emergencyresponse/displayemergencysituations', 'EmergencyResponseController@displayemergencysituationsajax')->name('emergency.displayemergencysituationsajax');
    Route::post('user/emergencyresponse/insertemergencydetails', 'EmergencyResponseController@insertemergencydetails')->name('emergency.insertemergencydetails');
    //-------------------------Drill report starts--------------//
    //create drill report
    Route::get('user/emergencyresponse/createdrillreport/{id}/{serialnum}', 'EmergencyResponseController@createdrillreport')->name('emergency.createdrillreport');
    Route::post('user/emergencyresponse/insertreportdetails', 'EmergencyResponseController@insertreportdetails')->name('emergency.insertreportdetails');
    //drill report images upload
    Route::get('user/emergencyresponse/addrillreportimage/', 'EmergencyResponseController@adddrillreportimage')->name('emergency.adddrillreportimage');
    Route::post('user/emergencyresponse/upload/store','EmergencyResponseController@fileStore')->name('emergency.image.store');
    Route::post('user/emergencyresponse/image/delete','EmergencyResponseController@fileDestroy')->name('emergency.image.destroy');
    //view drill report
    Route::get('user/emergencyresponse/viewdrillreport/{id}', 'EmergencyResponseController@viewdrillreport')->name('emergency.viewdrillreport');
    Route::get('user/emergencyresponse/viewdetailedreport/{id}', 'EmergencyResponseController@viewdetailedreport')->name('emergency.viewdetailedreport');
    //add corrective actions
    Route::get('user/emergencyresponse/drillreport/addcorrective/{id}', 'EmergencyResponseController@addcorrectivedrillreport')->name('emergency.addcorrectivedrillreport');
    Route::post('user/emergencyresponse/drillreport/insertcorrective', 'EmergencyResponseController@insertcorrectivedrillreport')->name('emergency.insertcorrectivedrillreport');
    //-------------------------End Drill report--------------//
    /**----------User routes for Emergency Response Module ends-----------*/

    //user routes for incident report
    Route::get('/user/incidents/register', 'IncidentReportController@index')->name('incidentregister');
    Route::get('/user/report/add', 'IncidentReportController@addreport');
    Route::post('/user/incident/submit', 'IncidentReportController@addreportconfirm')->name('incidentConfirm');
    Route::get('/user/incident/review/{id}/{dummy}', 'IncidentReportController@reviewReport');
    Route::post('/user/incident/reviewed', 'IncidentReportController@reviewedReport')->name('incidentReviewed');
    Route::get('/user/incident/change/investigators/{id}/{dummy}', 'IncidentReportController@changeInvestigators');
    Route::post('/user/incident/change/investigators/confirm', 'IncidentReportController@changedInvestigators')->name('changedInv');
    Route::get('/user/update/incident/report/{id}/{dummy}', 'IncidentReportController@updateIncidentReport');
    Route::get('/user/incidents/deletevitnessess', 'IncidentReportController@deleteWitness')->name('deleteVitnesses');
    Route::get('/user/updatesecond/incident/report/{id}/{dummy}', 'IncidentReportController@updateSecondIncidentReport');
    Route::get('/user/review/approve/investigation/{id}/{dummy}', 'IncidentReportController@reviewApproveInvestigation');
    Route::get('/user/incident/report/update/{id}/{dummy}', 'IncidentReportController@updateReviewedReport');
    Route::post('/user/incident/filtr', 'IncidentReportController@filter');
    Route::post('/user/incidents/updatedIncidentReport', 'IncidentReportController@updatedIncidentReport')->name('updatedReport');
    Route::post('/user/incidents/secondupdatedIncidentReport', 'IncidentReportController@updatedSecondIncidentReport')->name('updatedSecondReport');
    Route::get('/user/incidents/deleteIncidentReport', 'IncidentReportController@deleteIncidentReport')->name('deleteIncidentReport');
    Route::post('/user/update/reviewed/incident/report', 'IncidentReportController@updatedReviewedReport')->name('updatereviewedincidentreport');
    Route::post('/user/reviewed/investigation/report', 'IncidentReportController@reviewedApprovedInvestigation')->name('Investigationreview');
    Route::get('/user/review/approve/investigation/report/{id}/{dummy}', 'IncidentReportController@ApproveInvestigationreport');
    Route::post('/user/investigation/approve', 'IncidentReportController@approveRejIncident')->name('Investigationapprove');
    Route::get('/user/incidents/closed/incidents', 'IncidentReportController@closedincidents');
    Route::get('/user/incident/report/deleteincident/{id}/{dummy}', 'IncidentReportController@deleteincident');
    Route::get('/user/incident/correactions', 'IncidentReportController@mycorractions')->name('mycorractions');
    Route::get('/user/incident/closedcorrections', 'IncidentReportController@closedcorrections');
    //Route::get('/user/incident/corr/edit/{id}', 'IncidentReportController@editcorractions')->name('editcorractions');
    //Route::post('/user/incident/corr/update', 'IncidentReportController@corrupdate')->name('corrupdate');
    Route::get('/mr/edit/incident/report', 'IncidentReportController@mrEditIncident')->name('editincident');
    Route::post('/user/incident/edited', 'IncidentReportController@mrEditedIncident');
    Route::get('/user/incident/addcorrective/{id}/{dummy}', 'IncidentReportController@correctiveaction');
    Route::post('/user/incident/corrected', 'IncidentReportController@correctedAction')->name('incidentCorrected');
    Route::get('/user/incident/corrective/{id}', 'IncidentReportController@viewcorrective');

    //change reviewer
    Route::get('/user/incident/changereviewer/{id}', 'IncidentReportController@changereviewer');
    Route::post('/user/incident/changereviewer/update', 'IncidentReportController@changereviewerupdate');

    //change approver
    Route::get('/user/incident/changeapprover/{id}', 'IncidentReportController@changeapprover');
    Route::post('/user/incident/changeapprover/update', 'IncidentReportController@changeapproverupdate');

    //closure review
    Route::get('/user/incident/closurereview/{id}', 'IncidentReportController@closurereview');
    Route::post('/user/incident/closurereview/addinvestigatorsajax', 'IncidentReportController@addinvestigatorsajax')->name('incident.addinvestigatorsajax');
    Route::post('/user/incident/closurereview/confirmclosurereview', 'IncidentReportController@confirmclosurereview')->name('incident.confirmclosure');

    //change investigation reviewer
    Route::get('/user/incident/changeinvestigationreviewer/{id}', 'IncidentReportController@changeinvestigationreviewer');
    Route::post('/user/incident/changeinvestigationreviewer/update','IncidentReportController@changeinvestigationreviewerupdate')->name('incident.changeinvestigationreviewer');

    //change investigation approver
    Route::get('/user/incident/changeinvestigationapprover/{id}', 'IncidentReportController@changeinvestigationapprover');
    Route::post('/user/incident/changeinvestigationapprover/update','IncidentReportController@changeinvestigationapproverupdate')->name('incident.changeinvestigationapprover');

    //end

    //CONTEXT MANAGEMENT

    //Issue Register
    Route::get('/user/contextmgmt', 'ContextController@issuereg')->name('user.context.index');
    Route::get('/user/context/interestparty', 'ContextController@interestparty');
    Route::get('/user/context/changelogreg', 'ContextController@changelogreg')->name('user.context.changelogreg');
    Route::get('/user/context/closure/{id}', 'ContextController@closurereview')->name('closreviewcontext');
    Route::post('/user/context/closure_post', 'ContextController@closure')->name('closreviewcontext_post');
    Route::get('/user/context/closed/{id}', 'ContextController@closed')->name('user.context.closed');
    Route::get('/user/context/needMoreAction/{id}', 'ContextController@needMoreAction')->name('user.context.needMoreAction');

    Route::any('/user/context/view', 'ContextController@view')->name('user.view');
    Route::get('/user/context/changelog/{id}/show','ContextController@show');
    //Addissue
    Route::get('/user/context/addissue', 'ContextController@addissue');
    Route::post('/user/context/insertissue', 'ContextController@insertissue');
    //Addparty
    Route::get('/user/context/addparty', 'ContextController@addparty');
    Route::post('/user/context/insertparty', 'ContextController@insertparty');
    Route::get('/user/context/addmoreparty', 'ContextController@addmoreparty');
    Route::post('/user/context/insertmoreparty', 'ContextController@insertmoreparty');
    //ChangeLog
    Route::get('/user/context/changelog', 'ContextController@changelog');
    Route::post('/user/context/insertrequest', 'ContextController@insertrequest');
    //Review
    Route::get('/user/context/review', 'ContextController@adds');
    Route::post('/user/context/review', 'ContextController@updatechangereq');
    //Approval
    Route::get('user/context/approve', 'ContextController@addsapprove');
    Route::post('user/context/approve', 'ContextController@updateapprove');
    //delete
    Route::get('user/context/delete', 'ContextController@delete')->name('deleteissue');
    Route::get('user/context/deleteparty', 'ContextController@deleteparty')->name('deleteparty');
    Route::get('user/context/deletecr', 'ContextController@deletecr')->name('deletecr');
    //edit issues
    Route::get('user/context/editreq_issues', 'ContextController@editreq_issues')->name('editissues');
    Route::post('/user/context/editrequest_issues', 'ContextController@editrequest_issues');
    //edit party
    Route::get('user/context/editreq_party', 'ContextController@editreq_party')->name('editparty');
    Route::post('/user/context/editrequest_party', 'ContextController@editrequest_party');
    //edit cr
    Route::get('user/context/editreq_cr', 'ContextController@editreq_cr')->name('editcr');
    Route::post('/user/context/editrequest_cr', 'ContextController@editrequest_cr');

    Route::get('user/context/hundred/{id}', 'ContextController@hundred')->name('user.context.hundred');
    Route::get('user/context/five/{id}', 'ContextController@five')->name('user.context.five');
    Route::get('user/context/two/{id}', 'ContextController@two')->name('user.context.two');
    Route::get('user/context/zero/{id}', 'ContextController@zero')->name('user.context.zero');
    Route::post('user/context/closure', 'ContextController@closure')->name('user.context.closure_post');
    //changelog add ohs
    Route::get('/user/context/changelog/addohsrisk/{id}/{crid}', 'ContextController@addohsrisk');
    Route::post('user/context/changelog/ohsrisk/submit', 'ContextController@insertchlogohsrisk');
    //changelog add envaspect
    Route::get('/user/context/changelog/addenvaspect/{id}/{crid}', 'ContextController@addenvaspect');
    Route::post('/user/context/changelog/envaspect/insert', 'ContextController@insertchlogenvaspt');

    //waste management
    //add waste
    Route::get('/user/waste/addwastemaster', 'WasteController@addwastemaster');
    Route::post('/user/waste/insertwastemaster', 'WasteController@insertwastemaster');
    Route::get('/user/waste/transferwasterequest', 'WasteController@wasterequestform');
    Route::post('/user/waste/insertwasterequest', 'WasteController@insertwasterequest');
    //waste register blade
    Route::get('/user/waste/register', 'WasteController@register')->name('waste.register');
    Route::get('/user/wastemgmt', 'WasteController@register');
    Route::get('/user/waste/transferrequest', 'WasteController@folder');
    Route::get('/user/waste/transferschedule/{year}', 'WasteController@transferschedule');
    Route::get('/user/waste/deletetransferrequest/{id}', 'WasteController@deleteTransferRequest')->name('deletetransferrequest');
    Route::get('/user/waste/edittransferrequest/{id}', 'WasteController@editTransferRequest')->name('edittransferrequest');
    Route::post('/user/waste/updatetransferrequest', 'WasteController@updatetransferrequest')->name('updatetransferrequest');

    //delete  by mr
    Route::get('/user/waste/delete', 'WasteController@delete')->name('deletewaste');
    //edit by mr
    Route::get('/user/waste/editw', 'WasteController@editwastemr')->name('editw');
    Route::post('/user/waste/updatewaste', 'WasteController@updatewaste');
    //Route::post('/user/waste/submitwaste', 'WasteController@submitwaste');
    //HOD approve
    Route::get('/user/waste/hodapprove', 'WasteController@hodapproveform');
    Route::post('/user/waste/hodapproverequest', 'WasteController@hodapproveaction');
    Route::post('/user/waste/hodapprovereject', 'WasteController@hodapprovereject');
    Route::get('/user/waste/hodeditrequest', 'WasteController@hodeditrequestfrm');
    Route::post('/user/waste/hodupdaterequest', 'WasteController@hodupdaterequest');
    Route::get('/user/waste/hoddeleterequest', 'WasteController@hoddeleterequest');
    //Disposal/sales
    Route::get('/user/waste/disposalsalesrequest', 'WasteController@disposalsalesform');
    Route::post('/user/waste/insertdisposalsalesrequest', 'WasteController@insertdisposalsales');
    //review
    //Route::get('/user/waste/review', 'WasteController@reviewcheck');
    Route::get('/user/waste/review', 'WasteController@reviewform');
    Route::post('/user/waste/reviewrequest', 'WasteController@reviewaction');
    Route::post('/user/waste/reviewreject', 'WasteController@reviewreject');
    //approve
    Route::get('/user/waste/approve', 'WasteController@approveform');
    Route::post('/user/waste/approverequest', 'WasteController@approveaction');
    Route::post('/user/waste/approvereject', 'WasteController@approvereject');
    //edit request
    Route::get('/user/waste/editrequest', 'WasteController@editrequestfrm');
    Route::post('/user/waste/updaterequest', 'WasteController@updaterequest');
    //delete request
    Route::get('/user/waste/deleterequest', 'WasteController@deleterequest');
    //dispose
    Route::post('/user/waste/wastedispose', 'WasteController@wastedispose')->name('wastedispose');
    //analysis
    Route::get('/user/waste/analysis', 'WasteController@analysis');

    Route::get('/user/vendormgmt', 'VendorController@register')->name('vendor.index');
    Route::group(['prefix' => '/user/vendor', 'as' => 'vendor.'], function () {
        //vendor
        //add vendor
        Route::get('/addvendor', 'VendorController@addvendor');
        Route::post('/submit', 'VendorController@insertvendor');
        //vendor register blade
        Route::get('/register', 'VendorController@register');

        //vendor history blade
        Route::get('/history', 'VendorController@history');
        //edit vendor
        Route::get('/editvendor', 'VendorController@editvendor');
        Route::post('/edit', 'VendorController@updatevendor');

        //review vendor
        Route::get('/reviewvendor/{id}', 'VendorController@reviewVendor')->name('reviewvendor');
        Route::post('/review', 'VendorController@review')->name('review');

        //approve vendor
        Route::get('/approvevendor/{id}', 'VendorController@approvevendor')->name('approvevendor');
        Route::post('/approve', 'VendorController@approve')->name('approve');

        //ajax
        Route::post('/ajax-score', 'VendorController@ajaxEvalProc')->name('ajax_score');
        Route::get('/ajax-vendor', 'VendorController@ajaxVendor')->name('ajax');

        //search
        Route::any('/search', 'VendorController@search');
        //filter
        Route::any('/filtr', 'VendorController@filtr');

        //re-evaluate route for enduser
        //product
        Route::get('/reevaluate', 'VendorController@adds');
        //service provider
        Route::get('/reevaluate1', 'VendorController@addp');

        //delete  by mr
        Route::get('/delete', 'VendorController@delete')->name('deletevendor');
        //edit by mr
        Route::get('/editv', 'VendorController@editv')->name('editv');
        Route::post('/editven', 'VendorController@editven');

        //vendor report
        Route::get('/report', 'VendorController@report');
        Route::get('/viewreport', 'VendorController@viewreport');

        //to select proc by vendor evaluator
        Route::post('/role', 'VendorController@procrole');

        //on clicking reevaluate
        Route::get('/peval/{history_id?}/{reviewed?}', 'VendorController@peval')->name('peval');
        Route::get('/seval', 'VendorController@seval');
        //submitting the reevaluation checkbox result(hse_eval blade)
        Route::post('/sendhse', 'VendorController@evalhse');
        //submitting the reevaluation checkbox result(hse_eval blade)
        Route::post('/send', 'VendorController@evalenduser');

        //to select proc by vendor evaluator
        Route::get('/analysis', 'VendorController@analysis')->name('analysis');

        // Request Evaluation
        Route::post('/request-evaluation', 'VendorController@requestEvaluation')->name('request.evaluation');
    });

    //HR Routes

    //add job
    Route::get('/user/hr/addjob', 'HrController@addjob');
    Route::post('/user/hr/jobsubmit', 'HrController@insertjob');
    //add training
    Route::get('/user/hr/addtraining', 'HrController@addtraining');
    Route::post('/user/hr/trainingsubmit', 'HrController@inserttraining');
    //edit training
    Route::get('/user/hr/edittraining', 'HrController@edittraining');
    Route::post('/user/hr/updatetraining', 'HrController@updatetraining');
    //update status
    Route::get('/user/hr/updatestatus', 'HrController@updatestatus');
    Route::post('/user/hr/sendstatus', 'HrController@eval');
    //evaluate training
    Route::get('/user/hr/evaluate', 'HrController@evaluate');
    Route::post('/user/hr/evalqs', 'HrController@evalqstns');
    //add questions by training coordinator
    Route::get('/user/hr/addqstn', 'HrController@addqstns');
    Route::post('/user/hr/submitqstn', 'HrController@insertqstns');
    //delete qstn
    Route::delete('/user/hr/CarryQstnID', 'HrController@CarryQstnID');
    //edit questions
    Route::post('/user/hr/editqstns', 'HrController@updateqstns');
    //add employee list
    Route::get('/user/hr/emplist', 'HrController@emplist');
    Route::post('/user/hr/addemp', 'VendorController@addemp');
    // register blade
    Route::get('/user/hr/register', 'HrController@register');
    Route::get('/user/hr/trainingregister', 'HrController@tregister');
    Route::get('/user/hrjmgmt', 'HrController@register')->name('hr.register');
    Route::get('/user/hrtmgmt', 'HrController@tregister');
    Route::get('/user/employeelist', 'HrController@employeelist');
    //delete training by mr
    Route::get('/user/hr/deletetraining', 'HrController@deletetraining')->name('deletetraining');
    //edit training by mr
    Route::get('/user/hr/editt', 'HrController@edittraining_mr')->name('editt');
    Route::post('/user/hr/editmrtraining', 'HrController@editmrtraining');
    //delete job by mr
    Route::get('/user/hr/deletejob', 'HrController@deletejob')->name('deletejob');
    //edit job by mr
    Route::get('/user/hr/editj', 'HrController@editjob_mr')->name('editj');
    Route::post('/user/hr/editmrjob', 'HrController@editmrjob');
    //search
    Route::any('user/hr/jobsearch', 'HrController@jobsearch');

    //complaint management
    Route::get('/user/complaintmgmt', 'ComplaintController@register')->name('user.compliancemgmt');
    Route::get('/user/complaint/list', 'ComplaintController@list');
    Route::get('/user/complaint/my', 'ComplaintController@my');
    Route::get('/user/complaint/closedc', 'ComplaintController@closedc');
    Route::get('/user/complaint/add', 'ComplaintController@add');
    Route::post('/user/complaint/insertcomplaint', 'ComplaintController@insertcomplaint');
    Route::get('/user/complaint/addcustomer', 'ComplaintController@addcustomer');
    Route::post('/user/complaint/insertcustomer', 'ComplaintController@insertcustomer');
    Route::post('/user/complaint/delete', 'ComplaintController@delete')->name('deletecustomer');
    Route::post('/user/complaint/addcustomerexcel','ComplaintController@addCustomerExcel');
    Route::get('/user/complaint/getAllCustomersForAddComplaint','ComplaintController@getAllCustomersForAddComplaint');
    Route::post('/user/complaint/viewcustomermodal','ComplaintController@viewCustomerRequest');
    Route::get('/user/complaint/getAllCustomers','ComplaintController@getAllCustomers');
    Route::get('/user/complaint/deletecomplaint', 'ComplaintController@deletecomplaint')->name('deletecomplaint');
    Route::post('/user/complaint/editreq', 'ComplaintController@editreq')->name('editcustomer');
    Route::get('/user/complaint/editreq_session', 'ComplaintController@editreqFromSession')->name('editcustomer_session');
    Route::get('/user/complaint/editreqcomplaint', 'ComplaintController@editreqcomplaint')->name('editreqcomplaint');
    Route::post('/user/complaint/editrequest', 'ComplaintController@editrequest');
    Route::post('/user/complaint/editrequestcomplaint', 'ComplaintController@editrequestcomplaint')->name('editrequestcomplaint');
    Route::get('/user/complaint/getDataFromTableAjax', 'ComplaintController@getDataFromTableAjax');
    Route::get('/user/complaint/getCustomerAjax', 'ComplaintController@getCustomerAjax');
    Route::post('/user/complaint/addcustomerajax','ComplaintController@addCustomerAjax');
    Route::get('/user/complaint/getCategoryAjax','ComplaintController@getCategoryAjax');
    Route::get('/user/complaint/getPreviousComplaints', 'ComplaintController@getPreviousComplaints');
    Route::post('/user/complaint/export-data', 'ComplaintController@exportData')->name('complaint.exportData');

    //initial review
    Route::get('/user/complaint/review', 'ComplaintController@creview');
    Route::post('/user/complaint/review', 'ComplaintController@review');
    Route::get('user/complaint/disapprove', 'ComplaintController@disapprove');
    Route::post('user/complaint/disapproving', 'ComplaintController@disapproving')->name('complaintdissapprove');
    Route::get('/user/complaint/resubmit', 'ComplaintController@resubmit');
    Route::post('/user/complaint/resubmit', 'ComplaintController@resubmitcomplaint');
    //Actionstatus
    Route::get('/user/complaint/hundred', 'ComplaintController@hundred');
    Route::get('/user/complaint/five', 'ComplaintController@five');
    Route::get('/user/complaint/two', 'ComplaintController@two');
    Route::get('/user/complaint/zero', 'ComplaintController@zero');

    Route::get('/user/complaint/proposeaction', 'ComplaintController@action');
    Route::post('user/complaint/displayrootcorrectiveajax', 'ComplaintController@displayrootcorrectiveajax')->name('complaint.displayrootcorrectiveajax');
    Route::post('/user/complaint/proposeaction', 'ComplaintController@proposeaction');

    Route::get('/user/complaint/editproposeaction', 'ComplaintController@edit');
    Route::post('/user/complaint/editproposeaction', 'ComplaintController@editproposeaction');

    //Secondary review(Review Status)
    Route::get('/user/complaint/reviewaction', 'ComplaintController@reviewaction');
    Route::post('/user/complaint/reviewok', 'ComplaintController@reviewok');
    Route::get('/user/complaint/reviewnotok', 'ComplaintController@notok');
    Route::post('user/complaint/reviewnotok', 'ComplaintController@reviewnotok');
    //Submit investigation form
    Route::post('/user/complaint/submit-investigation', 'ComplaintController@submitInvestigationForm')->name('user.complaint.submit_investigation_form');
    //Closure review
    Route::get('/user/complaint/closure', 'ComplaintController@closure');
    Route::get('/user/complaint/changes/{id}/{status?}/{rid?}', 'ComplaintController@changes');
    Route::post('user/complaint/suggestchanges', 'ComplaintController@suggestchanges')->name('complaint.suggestchanges');
    Route::post('/user/complaint/closed', 'ComplaintController@closed');
    Route::get('/user/complaint/updateprogress/{id}', 'ComplaintController@updateProgress');
    Route::post('/user/complaint/updateprogresspost','ComplaintController@postUpdatePercentage');
    //------------register table columns activation starts(making table dynamic)-----------//
    Route::get('/user/complaint/register-table/active/{id}', 'ComplaintController@registertablecolumnactive')->name('complaint.register.table.column.activation');
    Route::get('/user/complaint/register-table/defaultview', 'ComplaintController@registertablecolumndefaultview')->name('complaint.register.table.column.defaultview');
    Route::get('/user/complaint/register-table/defaultviewProject', 'ComplaintController@registertablecolumndefaultviewProject')->name('complaint.register.table.column.defaultviewProject');
    Route::post('/user/complaint/register-table/removecolumn','ComplaintController@removecolumn')->name('complaint.register.table.column.removecolumn');
    Route::post('/user/complaint/register-table/update-position','ComplaintController@updatePosition')->name('complaint.register.table.column.updateposition');
    //------------ends register table columns activation-----------//
    //------------closed complaint table columns activation starts(making table dynamic)-----------//
    Route::get('/user/complaint/closed-table/active/{id}', [App\Http\Controllers\ComplaintController::class,'closedcomplainttablecolumnactive'])->name('complaint.closed.table.column.activation');
    Route::get('/user/complaint/closed-table/defaultview', [App\Http\Controllers\ComplaintController::class,'closedcomplainttablecolumndefaultview'])->name('complaint.closed.table.column.defaultview');
    Route::get('/user/complaint/closed-table/defaultviewProject', [App\Http\Controllers\ComplaintController::class,'closedcomplainttablecolumndefaultviewProject'])->name('complaint.closed.table.column.defaultviewProject');
    Route::post('/user/complaint/closed-table/removecolumn',[App\Http\Controllers\ComplaintController::class,'closedcomplaintremovecolumn'])->name('complaint.closed.table.column.removecolumn');
    Route::post('/user/complaint/closed-table/update-position',[App\Http\Controllers\ComplaintController::class,'closedcomplaintupdatePosition'])->name('complaint.closed.table.column.updateposition');
    //------------ends closed complaint table columns activation-----------//
    // ---------------------New Details Page Start----------------------//
    Route::get('/user/complaint/{id}/detail', 'ComplaintController@detail')->name('complaint.detail');
    Route::get('/user/complaint/{id}/detail-pdf', 'ComplaintController@exportPdf')->name('complaint.exportPdf');
    //----------------------New Details Page End------------------------//
    Route::get('/user/complaint/{id}/show', 'ComplaintController@show')->name('complaint.show');
    //change responsibility
    Route::get('/user/complaint/{id}/change-responsibilty', 'ComplaintController@changeResp')->name('complaint.changeresponsibility');
    Route::post('/user/complaint/changeRespRequest','ComplaintController@changeRespRequest');
    //Delete Complaint Attachments
    Route::post('/user/complaint/{id}/delete-issue','ComplaintController@deleteIssueAttachment');
    Route::post('/user/complaint/{id}/delete-action','ComplaintController@deleteActionAttachment');
    Route::post('/user/complaint/{id}/delete-complaint','ComplaintController@deleteComplaintAttachment');

    //analysis routes
    //ohs_analysis
    Route::get('/user/ohsrisk/analysis', 'OhsRiskController@analysis');
    Route::get('/user/compliance/companalysis', 'ComplianceController@analysis');
    Route::get('/user/actionsystem/analysis', 'ActionController@analysis');
    //Route::get('/user/audit/saveVal', 'ActionController@saveValues');
    //analysis
    Route::get('/user/internalaudit/analysis', 'InternalauditController@analysis');
    // //filter analysis
    Route::any('/user/internalaudit/analysisfilter', 'InternalauditController@analysisfilter');

    Route::get('/user/corrective/analysis', 'CorrectiveController@analysis');
    Route::get("/user/enviaspects/analysis", 'EnviaspectsController@analysisshow');

    //maintenance module
    Route::get('/user/maintenancemgmt', 'MaintenanceController@total')->name('maintenance.total');
    //delete
    Route::get('user/maintenance/delete', 'MaintenanceController@delete')->name('deletemaintenance');
    //edit request
    Route::get('user/maintenance/editreq', 'MaintenanceController@editreq')->name('editmaintenance');
    Route::post('/user/maintenance/editrequest', 'MaintenanceController@editrequest');

    Route::get('/user/maintenance/pmfolder', 'MaintenanceController@pmfolder');
    Route::get('/user/maintenance/pmplan/{dept}', 'MaintenanceController@pmplan');
    Route::get('/user/maintenance/testfolder', 'MaintenanceController@testfolder');
    Route::get('/user/maintenance/testplan/{dept}', 'MaintenanceController@testplan');

    Route::get('/user/maintenance/addtotal', 'MaintenanceController@addtotal');
    Route::post('/user/maintenance/totalsubmit', 'MaintenanceController@insertt');
    Route::get('/user/maintenance/breakfolder', 'MaintenanceController@breakfolder');
    Route::get('/user/maintenance/break/{dept}', 'MaintenanceController@break');

    Route::get('user/maintenance/req', 'MaintenanceController@req')->name('maintenance');
    Route::post('/user/maintenance/mrequest', 'MaintenanceController@mrequest');

    Route::get('/user/maintenance/review', 'MaintenanceController@review');
    Route::post('/user/maintenance/hodreview', 'MaintenanceController@hodreview');

    //--HSE OBSERVATIONS SYSTEM

    Route::group(['prefix' => '/user/hseobservation', 'as' => 'hseobservation.'], function () {
        Route::get('/issues/{type?}', 'HseObservationController@viewlist')->name('index');
        Route::get('/reopen/{id}', 'HseObservationController@reopen')->name('reopen');
        Route::post('/reopen', 'HseObservationController@reopenissue')->name('reopenissue');
        Route::get('/reviewstatus/{id}', 'HseObservationController@reviewstatus')->name('reviewstatus');
        Route::post('/reviewed_ok', 'HseObservationController@actionreviewed_ok')->name('approved');
        Route::get('/reviewed_notok', 'HseObservationController@notok_remarks')->name('notok_remarks');
        Route::post('/reviewed_notok', 'HseObservationController@notok_addremarks')->name('notok_addremarks');
        Route::get('/closure/{id}', 'HseObservationController@closure')->name('closure');
        Route::get('/changes', 'HseObservationController@changes')->name('changes');
        Route::post('/suggestchanges', 'HseObservationController@suggestchanges')->name('suggestchanges');
        Route::get('/closed', 'HseObservationController@closed')->name('closed');
        Route::get('/addissue', 'HseObservationController@addissue')->name('addissue');
        Route::post('/insert', 'HseObservationController@insertissue')->name('insertissue');
        Route::get('/openissue', 'HseObservationController@openissue')->name('openissue');
        Route::get('/editopen', 'HseObservationController@editopen')->name('editopen');
        Route::post('/updateopen', 'HseObservationController@updateopen')->name('updateopenissue');
        Route::get('/editrespondedissue', 'HseObservationController@editrespondedissue')->name('editrespondedissue');
        Route::post('/updaterespondedissue', 'HseObservationController@updaterespondedissue')->name('updaterespondedissue');
        Route::get('/closedissue', 'HseObservationController@closedissue')->name('closedissue');
        Route::get('/{id}/closedissue-detail', 'HseObservationController@showClosedIssueDetail')->name('show.closed.issue');
        Route::get('/analysis', 'HseObservationController@analysis')->name('analysis');
        Route::get('/hundred', 'HseObservationController@hundred')->name('hundred');
        Route::get('/five', 'HseObservationController@five')->name('five');
        Route::get('/two', 'HseObservationController@two')->name('two');
        Route::get('/zero', 'HseObservationController@zero')->name('zero');
        Route::get('/delete/{id}', 'HseObservationController@delete')->name('hsedeleteaction');
        Route::get('/editreq/{id}', 'HseObservationController@editreq')->name('hseeditaction');
        Route::post('/editrequest', 'HseObservationController@editrequest')->name('editrequest');
        Route::get('/editresp/{id}', 'HseObservationController@editresp')->name('editresp');
        Route::post('/submitresp', 'HseObservationController@submittresp')->name('submittresp');
        Route::get('/issues/{id}', 'HseObservationController@getListDetails');
        Route::get('/getIssueAttachment/{id}', 'HseObservationController@getIssueAttachment')->name('issue');
        Route::get('/getActionAttachment/{id}', 'HseObservationController@getActionAttachment')->name('action');
    });
    Route::post('user/hseobservation/displayrootcorrectiveajax', 'HseObservationController@displayrootcorrectiveajax')->name('hse.displayrootcorrectiveajax');


    //user route
    Route::get('/user/occupationhealth/register', 'OccupationalHealth@index')->name('employeehealthrecords');
    Route::get('/user/add/healthrecord/form', 'OccupationalHealth@addform');
    Route::post('/user/add/healthrecord', 'OccupationalHealth@addrecord')->name('addrecord');
    Route::get('/user/occupationhealth/injuryrecords', 'OccupationalHealth@showinjuryhistory');
    //Route::get('/user/add/injuryrecord/form', 'OccupationalHealth@injuryform');
    Route::get('/user/employee/health/addinjury/{empnum}', 'OccupationalHealth@addinjuryform')->name('viewinjuryform');
    Route::post('/user/occupationhealth/add/injuryrecords', 'OccupationalHealth@addinjuryrecord')->name('addinjuryrecord');
    Route::get('/user/occupationhealth/illnessrecords', 'OccupationalHealth@illnessrecord')->name('illnessrecord');
    //Route::get('/user/add/illnessrecord/form', 'OccupationalHealth@addillnessrecord')->name('addillnessrecord');
    Route::get('/user/employee/health/addillness/{empnum}', 'OccupationalHealth@addillnessform');
    Route::post('/user/add/illnessrecord/', 'OccupationalHealth@addillnessrecordconf')->name('addillnessrecordconf');
    Route::get('/user/update/injuryrecord/{id}', 'OccupationalHealth@updateinjuryrecord');
    Route::post('/user/update/injuryrecord', 'OccupationalHealth@updateconfinjuryrecord')->name('updateinjuryrecord');
    Route::get('/user/update/illnessrecord/{id}', 'OccupationalHealth@updateillnessrecord');
    Route::post('/user/update/inllnessrecord', 'OccupationalHealth@updateconfillnessrecord')->name('updateillnessrecord');
    Route::get('/user/employee/health/history/{id}', 'OccupationalHealth@emphealthhistory')->name('emphealthhistory');
    Route::get('/user/employee/health/record/update/{id}', 'OccupationalHealth@emphealthrecordupdate')->name('emphealthrecordupdate');
    Route::post('/user/employee/health/record/update', 'OccupationalHealth@emphealthrecordupdateconf')->name('emphealthrecordupdateconf');
    Route::get('/user/occupationhealth/healthrecord/delete', 'OccupationalHealth@delete')->name('deletehealthrecord');
    Route::get('/user/occupationhealth/employee/injuryrecord/delete', 'OccupationalHealth@injurydelete')->name('deleteemplyinjury');
    Route::get('/user/occupationhealth/employee/illnessrecord/delete', 'OccupationalHealth@illnessdelete')->name('deleteemplyillness');
    Route::get('/user/occupationhealth/analysis', 'OccupationalHealth@analysis');
    Route::post('/user/occupationhealth/selectinjurytype', 'OccupationalHealth@selectinjurytype')->name('selectinjurytype');
    Route::post('/user/occupationhealth/selectillnesstype', 'OccupationalHealth@selectillnesstype')->name('selectillnesstype');
    Route::get('/user/occupationhealth/othercases', 'OccupationalHealth@othercases')->name('healthothercases');
    Route::get('/user/occupationhealth/addotherinjuryillness', 'OccupationalHealth@addotherinjuryillness');
    Route::post('/user/occupationhealth/addotherinjuryrecords', 'OccupationalHealth@addotherinjuryrecords')->name('addotherinjuryrecords');
    Route::post('/user/occupationhealth/addotherillnessrecords', 'OccupationalHealth@addotherillnessrecords')->name('addotherillnessrecords');
    Route::get('/user/occupationhealth/update/otherinjuryrecord/{id}', 'OccupationalHealth@updateotherinjuryrecord');
    Route::post('/user/occupationhealth/update/otherinjuryrecord', 'OccupationalHealth@updateconfirmotherinjuryrecord')->name('updateotherinjuryrecords');
    Route::get('/user/occupationhealth/update/otherillnessrecord/{id}', 'OccupationalHealth@updateotherillnessrecord');
    Route::post('/user/occupationhealth/update/otherillnessrecord', 'OccupationalHealth@updateconfirmotherillnessrecord')->name('updateotherillnessrecords');
    Route::get('/user/occupationhealth/employee/othercasesrecord/delete', 'OccupationalHealth@deleteothercasesrecord')->name('deleteothercasesrecord');

    //Near Miss
    Route::group(['prefix' => '/user/nearmiss', 'as' => 'nearmiss.'], function () {
        //Route::get('/test', 'NearmissController@test')->name('test');
        Route::post('/ajax-get-info', 'NearmissController@ajaxForgivingDepartmentAndPosition')->name('ajax_get_info');
        //Route::get('/viewactivity-change', 'NearmissController@viewActivityDateChanges');
        Route::get('/addissue', 'NearmissController@addissue')->name('addissue');
        Route::post('/insert', 'NearmissController@insertissue')->name('insertissue');
        Route::get('/reopen/{id}', 'NearmissController@reopen')->name('reopen');
        Route::post('/reopen', 'NearmissController@reopenissue')->name('reopen_post');
        Route::get('/reviewstatus/{id}', 'NearmissController@reviewstatus')->name('reviewstatus');
        Route::get('/initial-review/{id}', 'NearmissController@intialReview')->name('intialreview');
        Route::post('/initial_reviewed_ok', 'NearmissController@initialreviewed_ok');
        Route::get('/initial_reviewed_notok/{id}', 'NearmissController@notok_initial_remarks');
        Route::post('/initial_reviewed_notok', 'NearmissController@notok_initial_addremarks')->name('initial_reviewed_notok');
        Route::post('/reviewed_ok', 'NearmissController@actionreviewed_ok')->name('approved');
        Route::get('/reviewed_notok/{id}', 'NearmissController@notok_remarks')->name('not_ok_remarks');
        Route::post('/reviewed_notok', 'NearmissController@notok_addremarks')->name('not_ok_add_remarks');
        Route::get('/closure/{id}', 'NearmissController@closure')->name('closure');
        Route::get('/changes/{id}', 'NearmissController@changes')->name('changes');
        Route::post('/suggestchanges', 'NearmissController@suggestchanges')->name('suggest');
        Route::get('/closed/{id}', 'NearmissController@closed')->name('closed');
        Route::get('/openissue', 'NearmissController@openissue')->name('openissue');
        Route::get('/editopen/{id}', 'NearmissController@editopen')->name('editopen');
        Route::post('/displayrootcorrectiveajax', 'NearmissController@displayrootcorrectiveajax')->name('displayrootcorrectiveajax');
        Route::post('/updateopen', 'NearmissController@updateopen')->name('updateopenissue');
        Route::get('/editrespondedissue/{id}', 'NearmissController@editrespondedissue')->name('editrespondedissue');
        Route::post('/updaterespondedissue', 'NearmissController@updaterespondedissue')->name('updaterespondedissue');
        Route::get('/closedissue', 'NearmissController@closedissue')->name('closedissue');
        Route::get('/analysis', 'NearmissController@analysis')->name('analysis');
        Route::post('/ajaxanalysis', 'NearmissController@ajaxAnalysis')->name('ajax_analysis');
        Route::get('/hundred/{id}', 'NearmissController@hundred')->name('hundred');
        Route::get('/five/{id}', 'NearmissController@five')->name('five');
        Route::get('/two/{id}', 'NearmissController@two')->name('two');
        Route::get('/zero/{id}', 'NearmissController@zero')->name('zero');
        Route::get('/delete/{id}', 'NearmissController@delete')->name('delete');
        Route::get('/editreq/{id}', 'NearmissController@editreq')->name('edit')->middleware('can:edit-nearmiss');
        Route::post('/editrequest', 'NearmissController@editrequest')->name('editrequest_post')->middleware('can:edit-nearmiss');
        Route::get('/editresp/{id}', 'NearmissController@editresp')->name('editresp');
        Route::post('/submitresp', 'NearmissController@submittresp')->name('submitresp');
        Route::get('/{type?}', 'NearmissController@viewlist')->name('index');
    });
    Route::get('/auditmgmt/{id}', 'ActionController@getListDetails');
    //Route::get('/getIssueAttachment/{id}', 'ActionController@getIssueAttachment');
    Route::post('/actionAttachment/{id}', 'ActionController@actionAttachment')->name('saveActionAttachment');
    Route::post('/correctiveActionAttachment/{id}', 'CorrectiveController@correctiveActionAttachment')->name('corrective.saveActionAttachment');
    Route::post('/HseActionAttachment/{id}', 'HseObservationController@hseActionAttachment')->name('hse.saveActionAttachment');
    Route::post('/NearmissActionAttachment/{id}', 'NearmissController@nearmissActionAttachment')->name('nearmiss.saveActionAttachment');
    Route::post('/ComplaintActionAttachment/{id}', 'ComplaintController@complaintActionAttachment')->name('complaint.saveActionAttachment');
    //Route::get('/getActionAttachment/{id}', 'ActionController@getActionAttachment');
    //Route::get('/audit-change', 'ActionController@saveValuesOfAudit');

    Route::group(['prefix' => '/user/location', 'as' => 'location.'], function () {

        Route::get('/ajaxDepartment/{id}', 'LocationController@getLocationsOfADepartment')->name('department');
        Route::get('/ajaxDivision/{id}', 'LocationController@getLocationsOfADivision')->name('division');
    });

    Route::group(['prefix' => '/user/division', 'as' => 'division.'], function () {

        Route::get('/ajaxLocation/{id}', 'DivisionController@getDivisionOfALocation')->name('location');
    });

    Route::group(['prefix' => '/user/ohsriskowners', 'as' => 'ohsriskowner.'], function () {

        Route::post('/ajaxChangeOhsriskowners', 'OhsRiskownerController@ajaxChangeRiskOwners')->name('change');
    });

    Route::group(['prefix' => '/countries', 'as' => 'country.'], function () {
        Route::get('/ajaxStates/{id}', 'CountryController@getState')->name('states');
    });

    //Customer satisfaction module (user panel)
    Route::get('/user/customermgmt', 'CustomerSatisfactionController@register')->name('customer.register');
    Route::group(['prefix' => '/user/customer', 'as' => 'customer.'], function () {
        Route::get('showQstn/{id}/{type?}', 'CustomerSatisfactionController@showQuestions')->name('showQstn');
        Route::get('history/{id}', 'CustomerSatisfactionController@history')->name('history');
        Route::post('ajaxEvaluation', 'CustomerSatisfactionController@ajaxEvaluation')->name('ajax_evaluation');
        Route::get('ajax-get-customer', 'CustomerSatisfactionController@ajaxGetCustomerDetails')->name('ajax_get_customer_details');
        Route::post('ajax-short-url', 'CustomerSatisfactionController@ajaxShortUrl')->name('ajax_short_url');
        Route::post('ajax-send-mail', 'CustomerSatisfactionController@ajaxSendMail')->name('ajax_send_mail');
        Route::post('excel-upload', 'CustomerSatisfactionController@excelUpload')->name('excel_upload');
    });

    Route::group(['prefix' => '/config', 'as' => 'config.'], function () {
        Route::post('edit', 'ConfigController@editProductName')->name('customer.editName');
    });

    Route::group(['prefix' => '/user/elearning', 'as' => 'elearning.'], function () {
        Route::get('courses', 'ElearningController@courses')->name('courses');
        Route::get('course/{id}', 'ElearningController@course')->name('course');
        Route::get('publish/{id}', 'ElearningController@publishCourse')->name('publish');
        Route::get('unpublish/{id}', 'ElearningController@unpublishCourse')->name('unpublish');
        Route::post('course', 'ElearningController@addCourse')->name('add_course');
        Route::get('delete/{id}', 'ElearningController@deleteCourse')->name('delete_course');
        Route::post('update', 'ElearningController@updateCourse')->name('update_course');
        Route::get('add-content/{id}', 'ElearningController@addContent')->name('add_content');
        Route::post('insert-content', 'ElearningController@insertContent')->name('insert_content');
        Route::get('rich-text/{id}', 'ElearningController@showRichText')->name('rich_text');
        Route::get('delete-content/{id}', 'ElearningController@deleteCourseContent')->name('delete_course_content');
        Route::get('delete-heading/{id}', 'ElearningController@deleteCourseHeading')->name('delete_course_heading');
        Route::post('update-heading', 'ElearningController@updateCourseHeading')->name('update_course_heading');
        Route::post('update-content', 'ElearningController@updateCourseContent')->name('update_course_content');
        Route::post('update-content-rich-text', 'ElearningController@updateContentRichText')->name('update_conent_rich_text');
        Route::get('add-like/{id}','ElearningController@addLike')->name('add_like');
        Route::get('mark-as-completed/{id}', 'ElearningController@markReadingAsCompleted')->name('mark_reading_as_completed');
        Route::post('review', 'ElearningController@addReview')->name('review');
    });

    Route::group(['prefix' => '/user/comment', 'as' => 'comment.'], function () {
        Route::post('add', 'CommentController@store')->name('store');
        Route::get('delete/{id}', 'CommentController@destroy')->name('destroy');
        Route::get('delete-reply/{id}', 'CommentController@deleteReply')->name('delete_reply');
    });
});
//***************************************************  Module Routes Lists - END ****************************************************************************************************************** */
