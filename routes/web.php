<?php

namespace App\Http\Controllers;

use App\Http\Controllers\FirebaseController;

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\CalendarController;
use App\Models\InventoryMove;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// require __DIR__.'/auth.php';

//

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

// use App\Http\Controllers\ProfileController;

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

Auth::routes(['register' => false]);

Route::get('/map', function () {
    return view('home_dashboard.test_map');
});
// Route::get('veh', function () {
//     Make::create(['name'=>'honda','description'=>'this is honda']);
//  });

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// customer Route


// 'afterauth'
Route::get('/company_registration', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::get('/company_registration_iframe', [RegisterController::class, 'showRegistrationForm'])->name('register_iframe');

Route::post('/company_register', [CompanyController::class, 'register_company_store'])->name('register_company');

Route::middleware('auth')->group(function () {
    Route::get('/register_process', [CompanyController::class, 'register_process'])->name('register.company');
    Route::get('/companies/register', [CompanyController::class, 'register_company'])->name('company.register');
    Route::post('/companies/change_active', [CompanyController::class, 'change_active_company'])->name('company.change_active');
    Route::post('/register_submit_details', [CompanyController::class, 'register_submit'])->name('register_submit_detail');
});



Route::get('/test_page', function () {
    return view('auth.register_end');
});


Route::post('/company_registration', [RegisterController::class, 'register']);
Route::group(['middleware' => ['auth', 'afterauth']], function () {
    // Registered before '/' so route('dashboard') keeps resolving to '/' (the last route with a name wins).
    Route::get('/dashboard', [DashboardController::class, 'indexNew'])->name('dashboard');
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/vehicle_dashboard', [VehicleDashboardController::class, 'index'])->name('vehicle_dashboard');
    Route::get('/driver_dashboard', [DriverDashboardController::class, 'index'])->name('driver_dashboard');
    Route::get('/financial_dashboard', [FinancialDashboardController::class, 'index'])->name('financial_dashboard');
    Route::get('/unauthorized', function () {
        return view('home_dashboard.no_permission_found');
    })->name('unauthorized');
    //Jasper
    Route::get('jasper/compile', [\App\Http\Controllers\Jasper\JasperController::class, 'compile']);
    Route::get('jasper/report/{name}/{ext?}', [\App\Http\Controllers\Jasper\JasperController::class, 'report']);
    // print route for monthly orders complete:
    Route::get('/print-order/{id}', [OrderController::class, 'printOrder'])->name('orders.print');
    // route for dashboard pages:
    Route::get('/sales', function () {
        return view('theme.index');
    })->name('sales.sale');
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
    Route::resource('account-types', AccountTypeController::class);
    Route::get('account-types/dropdown/{parent_id?}', [AccountTypeController::class, 'get_accounType_ajax']);
    Route::resource('accounts', AccountController::class);
    Route::get('accounts/account-type-no/{account_type_id}/{account_subtype_id}', [AccountController::class, 'get_code_no']);
    Route::resource('gl-journals', GlJournalController::class);
    Route::post('gl-journals/new/row/{gl_journal_id}', [GlJournalController::class, 'add_new_row'])->name('gl-journal.add_row');
    Route::post('gl-journals/destroy/row/{gl_journal_line_id}', [GlJournalController::class, 'destroy_row'])->name('gl-journal.destroy_row');
    Route::post('gl-journals/complete/document/{gl_journal_id}', [GlJournalController::class, 'complete'])->name('gl-journal.complete');
    Route::resource('maintenances', MaintenanceController::class);
    Route::resource('maintenance_approvals', MaintenanceApprovalsController::class);
    Route::patch('/maintainence/create/{id}', [MaintenanceController::class, 'create_maintainence'])->name('inspection.create_maintainence');

    Route::post('maintenances/destroy/row/{invoice_line_id}', [MaintenanceController::class, 'destroy_row'])->name('maintenances.destroy_row');
    Route::resource('inspections', InspectionController::class);
    Route::post('inspections/destroy/row/{invoice_line_id}', [InspectionController::class, 'destroy_row'])->name('inspections.destroy_row');

    Route::resource('customers', CustomerController::class);
    // broadcast message route:
    Route::resource('broadcast-messages', BroadcastMessageController::class);
    // Route::resource('machines',MachineController::class);
    // Route::resource('complaints',ComplaintController::class);
    // profile controller
    Route::get('user-profile', [UserProfileController::class, 'create'])->name('user-profile.create');
    Route::post('user-profile', [UserProfileController::class, 'updateprofile'])->name('user-profile.update');
    //Theme controller
    Route::post('update-theme', [ThemeController::class, 'update_theme'])->name('theme.update');
    Route::post('update-header', [ThemeController::class, 'update_header']);
    Route::post('update-sidebar', [ThemeController::class, 'update_sidebar']);
    //Route::delete('delete-row/{id}', [ThemeController::class,'delete_row']);
    Route::delete('delete-row/{id}', [RoleModuleController::class, 'delete_row']);
    // toll tax
    Route::resource('toll-taxes', TollTaxController::class);
    Route::post('toll-taxes/destroy/row/{invoice_line_id}', [TollTaxController::class, 'destroy_row'])->name('toll-tax.destroy_row');

    // Fuel expense
    Route::resource('fuel-expenses', FuelExpenseController::class);
    Route::post('fuel-expenses/destroy/row/{invoice_line_id}', [FuelExpenseController::class, 'destroy_row'])->name('fuel-expenses.destroy_row');



    // calendar routes
    Route::get('/calendar', [CalendarController::class, 'index'])->name('calendar.index');
    Route::post('/calendar/events', [CalendarController::class, 'getEvents'])->name('calendar.get_events');
    // Route::get('/calendar/vehicle/filter', [CalendarController::class, 'getFilterEvents'])->name('calendar.vehicle.filter');

    // post customer data from order
    Route::post('customer/modal/create', [CustomerController::class, 'create_customer_from_order'])->name('create.customer.form.order');

    // Service-providers
    Route::resource('service_providers', ServiceProviderController::class);
    // Route::resource('engineers',EngineerController::class);
    // Route::resource('engineer_complaints',EngineerComplaintController::class);
    // Route::resource('engineer_feedback',EngineerFeedbackController::class);
    // company route
    Route::resource('products', ProductController::class);
    Route::resource('activities', ActivityController::class);
    Route::resource('invoice-document-types', InvoiceDocumentTypeController::class);
    Route::resource('sales', SaleController::class);
    // Route::resource('branch_details',BranchDetailController::class);

    // userRole route
    Route::resource('role_modules', RoleModuleController::class);
    Route::resource('role_permission_types', RolePermissionTypeController::class)->only('destroy');

    Route::resource('roles', RoleController::class);
    Route::resource('role_permissions', RolePermissionController::class);
    Route::get('roles/module/create', [RoleController::class, 'role_module_create'])->name('roles.modules.create');
    Route::get('roles/module/edit/{role_id}', [RoleController::class, 'role_module_edit'])->name('roles.modules.edit');
    Route::post('roles/module/update/{role_id}', [RoleController::class, 'role_module_update'])->name('roles.modules.update');
    //actors route
    Route::resource('actors', ActorController::class);
    //users route
    Route::resource('users', UserController::class);
    //Resources route
    Route::resource('vehicles', VehicleController::class);
    Route::post('/get-vehicle-class-details', [VehicleController::class, 'getVehicleClassDetails'])->name('getVehicleClassDetails');
    Route::get('/fetch-timelogs', [VehicleController::class, 'get_time_logs']);

    //employee route
    Route::resource('employees', EmployeeController::class);
    Route::get('/partner/{type}/{partner_id}', [CustomerController::class, 'partner_dropdown']);
    //routes route
    Route::resource('routes', RouteController::class);
    // loadtypes route:
    Route::resource('load-types', LoadTypeController::class);
    //  unittypesv route
    Route::resource('unit-types', UnitMeasureController::class);
    Route::resource('ratelists', RateListController::class);
    // testing data tables stubs
    Route::resource('data-tables', DataTableController::class);
    //orders route
    Route::resource('orders', OrderController::class);
    Route::resource('agentorders', AgentOrderController::class);

    Route::resource('rental_vehicles', RentalVehicleController::class);
    Route::resource('pending_rental_invoices', PendingInvoicesController::class);
    Route::resource('tour_services', TourServiceController::class);
    Route::resource('daily_rentals', DailyRentalController::class);
    Route::delete('/delete-route-row/{id}', [OrderController::class, 'deleteRow'])->name('delete-route-row');
    Route::delete('/delete-activity-row/{id}', [ActivityController::class, 'deleteActivityRow'])->name('delete-activity-row');
    Route::delete('/delete-poline-row/{id}', [PurchaseOrderController::class, 'deleteActivityRow'])->name('delete-poline-row');
    Route::delete('/delete-product_price-row/{id}', [ProductController::class, 'deleteActivityRow'])->name('delete-product_price-row');
    Route::delete('/delete-version-row/{id}', [PriceListController::class, 'deleteActivityRow'])->name('delete-version-row');
    Route::delete('/delete-inoutline-row/{id}', [PurchaseOrderController::class, 'deleteActivityRow'])->name('delete-inoutline-row');
    Route::delete('/delete-movement_line-row/{id}', [InventoryMoveController::class, 'deleteActivityRow'])->name('delete-movement_line-row');
    Route::delete('/delete-phy_inv_line-row/{id}', [PhysicalInventoryController::class, 'deleteRow'])->name('delete-physical_inv-row');
    // approvals route
    Route::resource('pending_orders', PendingOrderController::class);
    //agents route
    Route::resource('business_agents', BusinessAgentController::class);
    Route::resource('vendors', VendorController::class);
    Route::resource('unapproved_agents', UnapprovedAgentController::class);
    //    company route:
    Route::resource('companies', CompanyController::class);
    // customer route
    Route::resource('customers', CustomerController::class);
    // drivers route
    Route::resource('drivers', DriverController::class);
    // vehicle class controller:
    Route::resource('vehicle_classes', VehicleClassController::class);
    // car company:
    Route::resource('vehicle-companies', VehicleCompanyController::class);
    // ------------------------------inventories modules----------------------------------

    Route::resource('manufacturing-companies', ManufacturingCompanyController::class);
    Route::resource('brands', BrandController::class);
    Route::resource('ware-houses', WareHouseController::class);
    Route::resource('locators', LocatorController::class);
    Route::resource('product-categories', ProductCategoryController::class);
    Route::resource('product-sub-categories', ProductSubCategoryController::class);
    Route::resource('product-types', ProductTypeController::class);
    Route::resource('purchase_orders', PurchaseOrderController::class);
    Route::resource('material_inout', MaterialInoutController::class);
    Route::resource('inventory_move', InventoryMoveController::class);
    Route::resource('inventory_consumptions', InventoryConsumptionController::class);
    Route::resource('purchase_invoices', PurchaseInvoiceController::class);
    Route::resource('physical_inventory', PhysicalInventoryController::class);
    Route::resource('product-costings', ProductCostingController::class);
    Route::resource('taxes', TaxController::class);
    Route::resource('m-match-pos', M_MatchPoController::class);

    Route::resource('stock-storages', StockStorageController::class);
    Route::resource('price-lists', PriceListController::class);
    Route::get('/fetch-order-details', [MaterialInoutController::class, 'fetchPoLines'])->name('fetch.order.lines');
    Route::get('/fetch-material-lines', [MaterialInoutController::class, 'fetchMaterialLines'])->name('fetch.material.lines');
    Route::get('/fetch-purchase-orders', [MaterialInoutController::class, 'fetchPurchaseOrders'])->name('fetch.purchase.orders');
    Route::get('/fetch-receipt', [MaterialInoutController::class, 'fetchReceipt'])->name('fetch.receipt');
    Route::get('/get-purchase-order', [MaterialInoutController::class, 'getPurchaseOrderDate'])->name('get.purchase.order');
    Route::get('/get-receipt_po', [MaterialInoutController::class, 'getReceiptOrderId'])->name('get.receipt_order_id');
    Route::resource('product-prices', ProductPriceController::class);
    Route::get('/fetch-product-price', [ProductController::class, 'fetchProductPrice'])->name('fetch.product.price');
    Route::get('/get-locators/{warehouseId}', [MaterialInoutController::class, 'getLocators'])->name('get.locators');
    Route::get('/check-available-quantity', [MaterialInoutController::class, 'checkAvailableQuantity']);
    Route::get('/check-available-MLquantity', [PurchaseInvoiceController::class, 'checkAvailableQuantity']);


    // ----------------------------End------------------------------------
    Route::get('/print-order/{id}', [PurchaseOrderController::class, 'printPurchaseOrder'])->name('purchase_orders.print');
    // vehicle location:
    // Route::get('/vehicle-location', [VehicleController::class, 'getVehicleLocation'])->name('vehicle_loc');
    Route::get('/vehicle/route-history', [OrderDetailRouteHistoryController::class, 'getRouteHistory'])->name('vehicle_loc');

    // vehicle model:
    Route::resource('vehicle-models', VehicleModelController::class);
    // driver assignments route:
    Route::resource('driver_assignments', DriverAssignmentController::class)->except(['show']);
    // receipt route:
    Route::get('order/receipts', [OrderController::class, 'receipt'])->name('order.receipts');
    Route::get('order/receipt_details/{id}', [OrderController::class, 'receiptDetails'])->name('order.receiptdetails');
    // assign vehicle ajax request:
    Route::post('/driver_assignments/assign_vehicle', [DriverAssignmentController::class, 'assignVehicle'])->name('driver_assignments.assign_vehicle');
    Route::get('/driver_assignments/assign_vehicle_ajax', [DriverAssignmentController::class, 'getAvailableVehiclesAjax'])->name('driver_assignments.assign_vehicle_ajax');
    // Route::delete('/driver_assignments/delete_order', [DriverAssignmentController::class, 'deleteOrder'])->name('driver_assignments.delete_order');
    // incomplete rides:
    Route::get('/driver_assignments/incomplete_rides', [DriverAssignmentController::class, 'incompleteRides'])->name('driver_assignments.incomplete_rides');
    Route::post('/driver_assignments/incomplete_rides', [DriverAssignmentController::class, 'incompleteRidesUpdate'])->name('driver_assignments.incomplete_rides_update');
    // Route::post('/driver_assignments/driver_update', [DriverAssignmentController::class, 'driverUpdate'])->name('driver_assignments.driver_update');
    Route::get('/driver_assignments/completed_rides', [DriverAssignmentController::class, 'completedRides'])->name('driver_assignments.completed_rides');
    Route::post('/driver_assignments/completed_rides', [DriverAssignmentController::class, 'completedRidesUpdate'])->name('driver_assignments.completed_rides_update');
    Route::get('/driver_assignments/cancelled_rides', [DriverAssignmentController::class, 'cancelledRides'])->name('driver_assignments.cancelled_rides');
    // Route::get('/vehicle', [DriverAssignmentController::class, 'vehicle_dropdown']);
    //cities route:

    Route::get('/get-cities', [CityController::class, 'getCities'])->name('get-cities');
    // bulkpayment route:
    Route::resource('bulkpayments', BulkPaymentController::class);
    Route::resource('payments', AgentPaymentController::class);
    Route::post('payments_update/{id}', [AgentPaymentController::class, 'update_status'])->name('payments.update_status');
    // routes/web.php
    Route::get('/customer/search', [CustomerController::class, 'search'])->name('customer.search');
    Route::get('payment_window/index', [BulkPaymentController::class, 'payment_window_index'])->name('payment_window.index');
    Route::get('payment_window/create', [BulkPaymentController::class, 'payment_window_create'])->name('payment_window.create');
    Route::post('payment_window/store', [BulkPaymentController::class, 'payment_window_store'])->name('payment_window.store');
    Route::get('payment_window/{id}/edit', [BulkPaymentController::class, 'payment_window_edit'])->name('payment_window.edit');
    Route::post('payment_window/{id}/update', [BulkPaymentController::class, 'payment_window_update'])->name('payment_window.update');
    // locations route
    Route::resource('locations', LocationController::class);
    // ledgers route:
    Route::resource('ledgers', LedgerController::class);
    Route::get('ledger/driver_ledger', [LedgerController::class, 'driver_ledger'])->name('ledger.driver_ledger');
    Route::get('/agent/search', [BusinessAgentController::class, 'search'])->name('agent.search');
    Route::get('/driver/search', [DriverController::class, 'driver_search'])->name('driver.search');
    // dashboard routes:
    Route::get('/order/{ordertype}', [DashboardController::class, 'orders_index'])->name('dashboard.orders');
    // partner locations route:
    Route::resource('partner-locations', PartnerLocationController::class);

    // export for admin:
    Route::get('/export_order', [DashboardController::class, 'export_orders'])->name('export.orders');
    Route::get('/export_pending_order', [DashboardController::class, 'export_pending_orders'])->name('export.pending.orders');
    Route::get('/overall_export_pending_order', [DashboardController::class, 'overall_export_pending_orders'])->name('overall.export.pending.orders');
    Route::get('/export_approved_order', [DashboardController::class, 'export_approved_orders'])->name('export.approved.orders');
    Route::get('/export_incomplete_order', [DashboardController::class, 'export_incomplete_orders'])->name('export.incomplete.orders');
    Route::get('/export_completed_order', [DashboardController::class, 'export_completed_orders'])->name('export.completed.orders');
    Route::get('/export_cancel_order', [DashboardController::class, 'export_cancel_orders'])->name('export.cancel.orders');
    Route::get('/export_unapproved_order', [DashboardController::class, 'export_unapproved_orders'])->name('export.unapproved.orders');
    //  export for agent:
    Route::get('/agent_export_order', [DashboardController::class, 'agent_export_orders'])->name('agent_export.orders');
    Route::get('/agent_export_pending_order', [DashboardController::class, 'agent_export_pending_orders'])->name('agent_export.pending.orders');
    Route::get('/overall_agent_export_pending_order', [DashboardController::class, 'overall_agent_export_pending_orders'])->name('overall.agent_export.pending.orders');
    Route::get('/agent_export_approved_order', [DashboardController::class, 'agent_export_approved_orders'])->name('agent_export.approved.orders');
    Route::get('/agent_export_incomplete_order', [DashboardController::class, 'agent_export_incomplete_orders'])->name('agent_export.incomplete.orders');
    Route::get('/agent_export_completed_order', [DashboardController::class, 'agent_export_completed_orders'])->name('agent_export.completed.orders');
    Route::get('/agent_export_cancel_order', [DashboardController::class, 'agent_export_cancel_orders'])->name('agent_export.cancel.orders');
    Route::get('/agent_export_unapproved_order', [DashboardController::class, 'agent_export_unapproved_orders'])->name('agent_export.unapproved.orders');
    // end...

    // export locations:
    Route::get('/export_location', [LocationController::class, 'export'])->name('export.locations');
    // end...
    // export routes:
    Route::get('/export_route', [RouteController::class, 'export'])->name('export.routes');
    // end...
    // export ratelists:
    Route::get('/export_ratelist', [RateListController::class, 'export'])->name('export.ratelists');
    // end...
    // export agents:
    Route::get('/export_agent', [BusinessAgentController::class, 'export'])->name('export.agents');
    // end...
    // export drivers:
    Route::get('/export_driver', [DriverController::class, 'export'])->name('export.drivers');
    // end...
    // export customers:
    Route::get('/export_customer', [CustomerController::class, 'export'])->name('export.customers');
    // end...
    // export models:
    Route::get('/export_models', [VehicleModelController::class, 'export'])->name('export.models');
    // end...
    // export company:
    Route::get('/export_company', [VehicleCompanyController::class, 'export'])->name('export.car_companies');
    // end...
    // export vehicles:
    Route::get('/export_vehicle', [VehicleController::class, 'export'])->name('export.vehicles');
    // end...
    // export agent_ledger:
    Route::get('/export_agent_ledger', [LedgerController::class, 'export'])->name('export.agent_ledgers');
    // end...
    // export agent_ledger:
    Route::get('/export_driver_ledger', [LedgerController::class, 'driver_export'])->name('export.driver_ledgers');
    // end...
    Route::get('/order/{ordertype}', [DashboardController::class, 'admin_rides_window'])->name('dashboard.orders');
    Route::get('/rides_status/{ordertype}', [DashboardController::class, 'agent_rides_window'])->name('agent.rides_window');
    Route::get('/total_rides', [DashboardController::class, 'total_rides'])->name('total-rides');


    // Route::get('/order/{ordertype}', [DashboardController::class, 'orders_index'])->name('dashboard.orders_agent');

    // one time password change for agent
    Route::get('/password/change', [PasswordChangeController::class, 'showChangePasswordForm'])->name('password.change');
    Route::post('/password/change', [PasswordChangeController::class, 'updatePassword'])->name('password.update');

    // Route::put('/change-password/{id}', [UserController::class, 'changePassword'])->name('users.change-password');
    Route::put('/change-password/{id}', [OrderController::class, 'changePassword'])->name('users.change-password');
    Route::put('/change-status/{id}', [VehicleController::class, 'changeStatus'])->name('vehicle.change-status');
});
// user complain Route.....
Route::group(['middleware' => 'auth'], function () {
    Route::get('/user-complaints-form', [CustomerController::class, 'index'])->name('user-complaints-form');
    Route::post('/user-details', [CustomerController::class, 'store']);
    Route::get('/user-delete/{id}', [CustomerController::class, 'destroy'])->name('user-delete');

    // Route::get('/machine-details-form',[ComplaintController::class, 'machinedetails'])->name('machine-details-form');
    // Route::post('/machine-details-store',[ComplaintController::class,'machineDataStore']);
    // Route::get('/machine-delete/{id}',[ComplaintController::class, 'destroyMachine'])->name('machine-delete');

    // Route::get('/complaint-details-form',[ComplaintController::class, 'complaintdetailsform'])->name('complaint-details-form');
    // Route::post('complaint-details-store',[ComplaintController::class ,'ComplaintsDetailsstore']);

    // engineer Route
    // Route::get('engineer-form',[EngineerController::class, 'index']);
    // Route::post('engineer-store',[EngineerController::class, 'engineerStore']);
    // Route::post('engineer-feedback-store',[EngineerController::class, 'EngFeedbackstore']);

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
Route::get('/theme', function () {
    return view('theme.index3');
});
Route::get('/app-emailbox', function () {
    return view('theme.app-emailbox');
});
Route::get('/app-emailread', function () {
    return view('app-emailread');
});
Route::get('/app-chat-box', function () {
    return view('theme.app-chat-box');
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
    return view('theme.widgets');
});
Route::get('/component-alerts', function () {
    return view('theme.component-alerts');
});
Route::get('/component-accordions', function () {
    return view('theme.component-accordions');
});
Route::get('/component-badges', function () {
    return view('theme.component-badges');
});
Route::get('/component-buttons', function () {
    return view('component-buttons');
});
Route::get('/component-cards', function () {
    return view('theme.component-cards');
});
Route::get('/component-carousels', function () {
    return view('theme.component-carousels');
});
Route::get('/component-list-groups', function () {
    return view('component-list-groups');
});
Route::get('/component-media-object', function () {
    return view('component-media-object');
});
Route::get('/component-modals', function () {
    return view('theme.component-modals');
});
Route::get('/component-navs-tabs', function () {
    return view('component-navs-tabs');
});
Route::get('/component-navbar', function () {
    return view('theme.component-navbar');
});
Route::get('/component-paginations', function () {
    return view('theme.component-paginations');
});
Route::get('/component-popovers-tooltips', function () {
    return view('theme.component-popovers-tooltips');
});
Route::get('/component-progress-bars', function () {
    return view('component-progress-bars');
});
Route::get('/component-spinners', function () {
    return view('theme.component-spinners');
});
Route::get('/component-notifications', function () {
    return view('theme.component-notifications');
});
Route::get('/component-avtars-chips', function () {
    return view('theme.component-avtars-chips');
});
/*Content*/
Route::get('/content-grid-system', function () {
    return view('theme.content-grid-system');
});
Route::get('/content-typography', function () {
    return view('theme.content-typography');
});
Route::get('/content-text-utilities', function () {
    return view('content-text-utilities');
});
/*Icons*/
Route::get('/icons-line-icons', function () {
    return view('theme.icons-line-icons');
});
Route::get('/icons-boxicons', function () {
    return view('theme.icons-boxicons');
});
Route::get('/icons-feather-icons', function () {
    return view('theme.icons-feather-icons');
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
Route::get('/authentication-forgot-password', function () {
    return view('theme.authentication-forgot-password');
});
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
    return view('theme.pricing-table');
});
Route::get('/errors-404-error', function () {
    return view('theme.errors-404-error');
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
Route::get('/service-provider', function () {
    return view('serviceprovider.serviceProvider');
});
Route::get('/new-complains', function () {
    return view('table-basic-table');
});
// Route::get('/user-comlaints-form',[ComplaintController::class, 'index']);
// Route::post('/user-details',[ComplaintController::class, 'store']);

// Route::get('/machine-details-form',[ComplaintController::class, 'machinedetails'])->name('machine-details-form');
// Route::post('/machine-data-store',[ComplaintController::class, 'machineDataStore']);

Route::get('/form-layouts', function () {
    return view('theme.form-layouts');
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
    return view('theme.form-validations');
});
Route::get('/form-wizard', function () {
    return view('theme.form-wizard');
});
Route::get('/form-text-editor', function () {
    return view('theme.form-text-editor');
});
Route::get('/form-file-upload', function () {
    return view('form-file-upload');
});
Route::get('/form-date-time-pickes', function () {
    return view('theme.form-date-time-pickes');
});
Route::get('/form-select2', function () {
    return view('theme.form-select2');
});
/*Maps*/
Route::get('/map-google-maps', function () {
    return view('theme.map-google-maps');
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

// notifications
Route::post('/save-firebase-token', [NotificationController::class, 'savetoken']);
Route::post('/test', [FirebaseController::class, 'test_firebase']);
Route::get('/logintest', [LoginController::class, 'login']);
