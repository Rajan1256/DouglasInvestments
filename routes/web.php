<?php

//Route::redirect('/', '/login');
use App\Http\Controllers\Auth\AuthController;


/* ---------------------------------- User Routes ------------------------------*/

Route::get('/', function () {
    return view('userlogin');
});

Route::get('/porfolio-manager-sign-in', function () {
    return view('portfolio-manager-login');
});

Route::get('sa-id-verification/{id}',[AuthController::class,'getSaVerifyLogin']);


Route::post('get-sharepoint-filter-data', [AuthController::class, 'sharepointData'])->name('sharepoint.post');
Route::post('user-login', [AuthController::class, 'postLogin'])->name('userlogin.post');
Route::post('sa-login', [AuthController::class, 'postsaLogin'])->name('salogin.post');
Route::post('portfolio-login', [AuthController::class, 'portfolioLogin'])->name('portfoliouserlogin.post');
Route::group(['middleware' => 'preventBackHistory'], function () {
    Auth::routes();
   // Route::get('go-to-dashbaord/{id}',[AuthController::class,'getDashboard']);
    Route::get('user-invested-company/{id}',[AuthController::class,'getalldata'])->name('investment_company');
    Route::get('dashboard', [AuthController::class, 'dashboard']);
});
Route::get('logout', [AuthController::class, 'logout']);





/* ---------------------------------- Admin Routes ------------------------------*/
Route::get('/home', function () {
    if (session('status')) {
        return redirect()->route('admin.home')->with('status', session('status'));
    }
    return redirect()->route('admin.home');
});

Auth::routes(['register' => false]);

Route::group(['prefix' => 'admin', 'as' => 'admin.', 'namespace' => 'Admin', 'middleware' => ['auth']], function () {
    Route::get('/', 'HomeController@index')->name('home');
    // Permissions
    Route::delete('permissions/destroy', 'PermissionsController@massDestroy')->name('permissions.massDestroy');
    Route::resource('permissions', 'PermissionsController');

    // Roles
    Route::delete('roles/destroy', 'RolesController@massDestroy')->name('roles.massDestroy');
    Route::resource('roles', 'RolesController');

    // Users
    Route::get('sendmailtoall', function () {
        return view('admin.testmail.send_mail_to_all_user');
    })->name('users.sendmailtoall');

    Route::get('testmail','UsersController@testMailPage')->name('users.testmail');
    Route::post('users/syncUserCompletedMail','UsersController@sendMailToAllUser')->name('users.syncUserCompletedMail');
    Route::post('users/sendMail','UsersController@sendMailToUser')->name('users.sendmail');
    Route::post('users/send_test_mail','UsersController@sendtestmail')->name('users.sendtestmail');
    Route::get('users/folders_level2/{userid}','UsersController@getCompany')->name('users.companys');
    Route::get('users/folders_level3/{userid}/{companyid}','UsersController@getCompanyYear')->name('users.company.years');
    Route::get('users/folders_file/{userid}/{companyid}/{fileid}','UsersController@getCompanyYearFile')->name('users.company.year.files');
    Route::post('users/syncuser','UsersController@syncUser')->name('users.syncUser');
    Route::delete('users/destroy', 'UsersController@massDestroy')->name('users.massDestroy');
    Route::resource('users', 'UsersController');

    //Portfolio Manager
    Route::delete('managers/destroy', 'PortfolioManagerDBController@massDestroy')->name('managers.massDestroy');
    Route::resource('managers', 'PortfolioManagerDBController');

    //Admin
    Route::delete('admins/destroy', 'AdminController@massDestroy')->name('admins.massDestroy');
    Route::resource('admins', 'AdminController');

    // Invest Company
    Route::delete('invest-companies/destroy', 'InvestCompanyController@massDestroy')->name('invest-companies.massDestroy');
    Route::resource('invest-companies', 'InvestCompanyController');

    // User Note Sync Yet
    Route::get('not-sync-yet','SyncController@index')->name('users.not_sync_yet');
});
Route::post('adminlogout', [AuthController::class, 'adminlogout'])->name('logoutadmin');



Route::group(['prefix' => 'profile', 'as' => 'profile.', 'namespace' => 'Auth', 'middleware' => ['auth']], function () {
    // Change password
    if (file_exists(app_path('Http/Controllers/Auth/ChangePasswordController.php'))) {
        Route::get('password', 'ChangePasswordController@edit')->name('password.edit');
        Route::post('password', 'ChangePasswordController@update')->name('password.update');
        Route::post('profile', 'ChangePasswordController@updateProfile')->name('password.updateProfile');
        Route::post('profile/destroy', 'ChangePasswordController@destroy')->name('password.destroyProfile');
    }
});
