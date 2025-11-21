<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

require __DIR__.'/auth.php';

// use App\Http\Controllers\Auth\LoginController;


// use App\Http\Controllers\ComplaintController;
// use App\Http\Controllers\CustomerController;
// use App\Http\Controllers\EngineerController;
// use App\Models\Product;
// use App\Models\EngineerModel;



// use App\Http\Controllers\RoleController;
// use App\Http\Controllers\RoleModulesController;
// use App\Http\Controllers\RolePermissionController;

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

// Auth::routes();

// Auth route............

//  Route::get('/signup', [LoginController::class, 'signupform']);
//  Route::post('/signup', [LoginController::class, 'signup'])->name('signup');
// Route::get('/login', [LoginController::class, 'LoginForm'])->name('login');
// Route::post('/login_attempt', [LoginController::class, 'login'])->name('login_attempt');

// Route::any('logout',function(){
//     Auth::logout();
//     return redirect()->route('login');
// })->name('logout');

//Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
// Route::get('/forgot-password', [LoginController::class, 'showForgotPasswordForm'])->name('password.request');
// Route::post('/forgot-password', [LoginController::class, 'forgotPassword'])->name('password.email');


use App\Http\Controllers\ProfileController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


// customer Route
Route::group(['middleware' => 'auth'], function () {
    Route::get('/', function () {
            return view('theme.index');
        });
// route for dashboard pages:
    Route::get('/sales', function () {
        return view('theme.index2');
     });
    Route::get('/ecommerce', function () {
        return view('theme.index3');
    });
    Route::get('/alternate', function () {
        return view('theme.index4');
    });
    Route::get('/hospitality', function () {
        return view('theme.index5');
    });
//resource routes:
Route::resource('customers',CustomerController::class);
Route::resource('machines',MachineController::class);
Route::resource('complaints',ComplaintController::class);
// profile controller
 //Route::resource('user-profile', ProfileController::class)->except('index');
Route::get('user-profile', [ProfileController::class,'create'])->name('user-profile.create');
Route::post('user-profile', [ProfileController::class,'updateprofile'])->name('user-profile.update');
Route::post('update-theme', [ThemeController::class,'update_theme'])->name('theme.update');





// Service-providers
Route::resource('service_providers',ServiceProviderController::class);
Route::resource('engineers',EngineerController::class);
Route::resource('engineer_complaints',EngineerComplaintController::class);
Route::resource('engineer_feedback',EngineerFeedbackController::class);
// company route
Route::resource('products',ProductController::class);
Route::resource('sales',SaleController::class);
Route::resource('branch_details',BranchDetailController::class);

// userRole route
Route::resource('role_modules',RoleModuleController::class);
Route::resource('roles',RoleController::class);
Route::resource('role_permissions',RolePermissionController::class);

});
// user complain Route.....
Route::group(['middleware' => 'auth'], function () {
    Route::get('/user-complaints-form',[CustomerController::class, 'index'])->name('user-complaints-form');
    Route::post('/user-details',[CustomerController::class, 'store']);
    Route::get('/user-delete/{id}',[CustomerController::class, 'destroy'])->name('user-delete');

    Route::get('/machine-details-form',[ComplaintController::class, 'machinedetails'])->name('machine-details-form');
    Route::post('/machine-details-store',[ComplaintController::class,'machineDataStore']);
    Route::get('/machine-delete/{id}',[ComplaintController::class, 'destroyMachine'])->name('machine-delete');

    Route::get('/complaint-details-form',[ComplaintController::class, 'complaintdetailsform'])->name('complaint-details-form');
    Route::post('complaint-details-store',[ComplaintController::class ,'ComplaintsDetailsstore']);
    
    // engineer Route
    Route::get('engineer-form',[EngineerController::class, 'index']);
    Route::post('engineer-store',[EngineerController::class, 'engineerStore']);
    Route::post('engineer-feedback-store',[EngineerController::class, 'EngFeedbackstore']);



});
// <----------------------------------------------------------------------------------------------------->





// Route::post('rolresource-delete/{id}',[RoleModulesController::class,'Destory'])->name('roleModuleDelete');

// role routes
// Route::get('role-form',[RoleController::class,'index']);
// Route::get('role-create',[RoleController::class,'create']);
// Route::post('role-data-store',[RoleController::class,'store']);
// Route::get('role-data-table',[RoleController::class,'roleTable']);
// Route::get('role-data-edit/{id}',[RoleController::class,'edit']);
// Route::post('role-data-update/{id}',[RoleController::class,'update'])->name('update-record');
// Route::get('roledelete/{id}',[RoleController::class,'Destory'])->name('roleDelete');

// Route for Role and permission

// Route::get('role-form',[RolePermissionController::class,'index']);
// Route::post('permission-data-store',[RolePermissionController::class,'store']);





// Route::get('/', function () {
//     return view('theme.index');
// });
// Route::get('/index', function () {
//     return view('index');
// });

/*App*/
Route::get('/app-emailbox', function () {
    return view('app-emailbox');
});
Route::get('/app-emailread', function () {
    return view('app-emailread');
});
Route::get('/app-chat-box', function () {
    return view('app-chat-box');
});
Route::get('/app-file-manager', function () {
    return view('app-file-manager');
});
Route::get('/app-contact-list', function () {
    return view('app-contact-list');
});
Route::get('/app-to-do', function () {
    return view('app-to-do');
});
Route::get('/app-invoice', function () {
    return view('theme.app-invoice');
});
Route::get('/app-fullcalender', function () {
    return view('theme.app-fullcalender');
});
/*Charts*/
Route::get('/charts-apex-chart', function () {
    return view('charts-apex-chart');
});
Route::get('/charts-chartjs', function () {
    return view('charts-chartjs');
});
Route::get('/charts-highcharts', function () {
    return view('charts-highcharts');
});
/*ecommerce*/
Route::get('/ecommerce-products', function () {
    return view('ecommerce-products');
});
Route::get('/ecommerce-products-details', function () {
    return view('ecommerce-products-details');
});
Route::get('/ecommerce-add-new-products', function () {
    return view('ecommerce-add-new-products');
});
Route::get('/ecommerce-orders', function () {
    return view('ecommerce-orders');
});

/*Components*/
Route::get('/widgets', function () {
    return view('widgets');
});
Route::get('/component-alerts', function () {
    return view('component-alerts');
});
Route::get('/component-accordions', function () {
    return view('component-accordions');
});
Route::get('/component-badges', function () {
    return view('component-badges');
});
Route::get('/component-buttons', function () {
    return view('component-buttons');
});
Route::get('/component-cards', function () {
    return view('component-cards');
});
Route::get('/component-carousels', function () {
    return view('component-carousels');
});
Route::get('/component-list-groups', function () {
    return view('component-list-groups');
});
Route::get('/component-media-object', function () {
    return view('component-media-object');
});
Route::get('/component-modals', function () {
    return view('component-modals');
});
Route::get('/component-navs-tabs', function () {
    return view('component-navs-tabs');
});
Route::get('/component-navbar', function () {
    return view('component-navbar');
});
Route::get('/component-paginations', function () {
    return view('component-paginations');
});
Route::get('/component-popovers-tooltips', function () {
    return view('component-popovers-tooltips');
});
Route::get('/component-progress-bars', function () {
    return view('component-progress-bars');
});
Route::get('/component-spinners', function () {
    return view('component-spinners');
});
Route::get('/component-notifications', function () {
    return view('component-notifications');
});
Route::get('/component-avtars-chips', function () {
    return view('component-avtars-chips');
});
/*Content*/
Route::get('/content-grid-system', function () {
    return view('content-grid-system');
});
Route::get('/content-typography', function () {
    return view('content-typography');
});
Route::get('/content-text-utilities', function () {
    return view('content-text-utilities');
});
/*Icons*/
Route::get('/icons-line-icons', function () {
    return view('icons-line-icons');
});
Route::get('/icons-boxicons', function () {
    return view('icons-boxicons');
});
Route::get('/icons-feather-icons', function () {
    return view('icons-feather-icons');
});




/*Authentication*/
// Route::get('/authentication-signin', function () {
//     return view('auth.authentication-signin');
// });
// Route::get('/authentication-signup', function () {
//     return view('auth.authentication-signup');
// });


Route::get('/authentication-signin-with-header-footer', function () {
    return view('authentication-signin-with-header-footer');
});
Route::get('/authentication-signup-with-header-footer', function () {
    return view('authentication-signup-with-header-footer');
});
// Route::get('/authentication-forgot-password', function () {
//     return view('theme.authentication-forgot-password');
// });
Route::get('/authentication-reset-password', function () {
    return view('authentication-reset-password');
});
Route::get('/authentication-lock-screen', function () {
    return view('authentication-lock-screen');
});
/*Table*/
Route::get('/table-basic-table', function () {
    return view('theme.table-basic-table');
});
Route::get('/table-datatable', function () {
    return view('table-datatable');
});
/*Pages*/
// Route::get('/user-profile', function () {
//     return view('theme.user-profile');
// });
Route::get('/timeline', function () {
    return view('timeline');
});
Route::get('/pricing-table', function () {
    return view('pricing-table');
});
Route::get('/errors-404-error', function () {
    return view('errors-404-error');
});
Route::get('/errors-500-error', function () {
    return view('errors-500-error');
});
Route::get('/errors-coming-soon', function () {
    return view('errors-coming-soon');
});
Route::get('/error-blank-page', function () {
    return view('error-blank-page');
});
Route::get('/faq', function () {
    return view('faq');
});
/*Forms*/
// Route::get('/user-details', function () {
//     return view('complaintsfolder.userdetails');
// });



// service provider
Route::get('/service-provider', function(){
    return view('serviceprovider.serviceProvider');
});
Route::get('/new-complains', function(){
    return view('table-basic-table');
});
// Route::get('/user-comlaints-form',[ComplaintController::class, 'index']);
// Route::post('/user-details',[ComplaintController::class, 'store']);

// Route::get('/machine-details-form',[ComplaintController::class, 'machinedetails'])->name('machine-details-form');
// Route::post('/machine-data-store',[ComplaintController::class, 'machineDataStore']);


Route::get('/form-layouts', function () {
    return view('form-layouts');
});

Route::get('/complaints-details', function () {
    return view('complaintsfolder.complaintsdetails');
});

Route::get('/engineer-deatils', function () {
    return view('engineerfolder.index');
});
Route::get('/engineer-feedback', function () {
    return view('engineerfolder.engineerfeedback');
});
Route::get('/form-validations', function () {
    return view('form-validations');
});
Route::get('/form-wizard', function () {
    return view('form-wizard');
});
Route::get('/form-text-editor', function () {
    return view('form-text-editor');
});
Route::get('/form-file-upload', function () {
    return view('form-file-upload');
});
Route::get('/form-date-time-pickes', function () {
    return view('form-date-time-pickes');
});
Route::get('/form-select2', function () {
    return view('form-select2');
});
/*Maps*/
Route::get('/map-google-maps', function () {
    return view('map-google-maps');
});
Route::get('/map-vector-maps', function () {
    return view('map-vector-maps');
});
Route::get('/downloads', function () {
    return view('downloads');
});
Route::get('/earnings', function () {
    return view('earnings');
});
/*Un-found*/
Route::get('/test/content-grid-system', function () {
    return view('test/content-grid-system');
});
