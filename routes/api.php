<?php

use App\Http\Controllers\Api\ActorController;
use App\Http\Controllers\Api\BlogController;
use App\Http\Controllers\Api\BusinessAgentController;
use App\Http\Controllers\Api\ChangePasswordController;
use App\Http\Controllers\Api\CityController;
use App\Http\Controllers\Api\CountrycodeController;
use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\PartnerController;
use App\Http\Controllers\Api\RateListController;
use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\DriverAssignmentController;
use App\Http\Controllers\Api\DriverController;
use App\Http\Controllers\Api\EmployeeController;
use App\Http\Controllers\Api\FirebaseController;
use App\Http\Controllers\Api\VehicleController;
use App\Http\Controllers\Api\ForgotPasswordController;
use App\Http\Controllers\Api\LedgerController;
use App\Http\Controllers\Api\LocationController;
use App\Http\Controllers\Api\ResetPasswordController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\PendingOrderController;
use App\Http\Controllers\Api\RouteController;
use App\Http\Controllers\Api\VehicleClassController;
use App\Http\Controllers\Api\VehicleModelController;
use App\Http\Controllers\Api\TimeLogController;
use App\Http\Controllers\Api\TollTaxController;
use App\Http\Controllers\Api\FuelExpenseController;
use App\Http\Controllers\Api\VehicleCompanyController;
use App\Http\Controllers\Api\CompanyController;
use App\Http\Controllers\Api\AdminOrderController;
use App\Http\Controllers\Api\LoadTypeController;
use App\Http\Controllers\Api\UnitMeasureController;
use App\Http\Controllers\Api\WhatsAppController;
use App\Http\Controllers\Api\MonthlyRentalVehicleController;
use App\Http\Controllers\Api\DailyRentalController;
use App\Http\Controllers\Api\TourServiceController;
use App\Http\Controllers\Api\AccountTypeController;
use App\Http\Controllers\Api\AccountController;
use App\Http\Controllers\DriverAssignmentController as ControllersDriverAssignmentController;
use App\Models\Blog;
use App\Models\Driver;
use App\Models\Order;
use App\Models\RouteRate;
use App\Notifications\RideStatusChanged;
use Composer\Package\Package;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

// Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
//     return $request->user();
// });
// login
Route::post('/register', [LoginController::class, 'register']);
Route::get('/get-user', [DriverAssignmentController::class, 'getAuthenticatedUser']);
Route::post('/login', [LoginController::class, 'login']);
Route::post('/change-password', [ChangePasswordController::class, 'changePassword']);
Route::post('/logout', [LoginController::class, 'logout'])->middleware('auth:sanctum');



// see all Ratelists
Route::get('/packages', [RateListController::class, 'api_index']);
Route::post('/create-ratelist', [RateListController::class, 'api_store']);
Route::get('/show-ratelist/{id}', [RateListController::class, 'api_show']);
Route::post('/update-ratelist/{ratelist}', [RateListController::class, 'api_update']);
Route::delete('/ratelist/delete/{id}', [RateListController::class, 'api_destroy']);





//    Agent   order
Route::get('/fetch-orders', [OrderController::class, 'api_index']);
Route::post('/api-draft-order', [OrderController::class, 'api_store_draft']);
Route::get('/partners_orders',[OrderController::class, 'api_login_partner_orders']);
Route::post('/create-order', [OrderController::class, 'api_store']);
Route::get('/show-order/{id}', [OrderController::class, 'api_show']);
Route::get('/edit-order/{id}', [OrderController::class, 'api_edit']);
Route::post('/update-order/{order}', [OrderController::class, 'api_update']);
Route::get('current-orders', [OrderController::class, 'currentorders']);
Route::get('status-count', [OrderController::class, 'getStatusCount']);
Route::get('latest-Pending-Orders', [OrderController::class, 'latestPendingOrders']);
Route::post('/order/draft', [OrderController::class, 'saveDraft']);
Route::post('/order/updatestatus/{id}', [OrderController::class, 'updateOrderStatus']);

//  Admin Order

Route::get('/admin-fetch-orders', [AdminOrderController::class, 'api_index']);
Route::get('/fetch-built-to', [AdminOrderController::class, 'api_built_to']);
Route::post('/admin-api-draft-order', [AdminOrderController::class, 'api_store_draft']);
Route::get('/admin-partners_orders',[AdminOrderController::class, 'login_partner_orders']);
Route::post('/admin-create-order', [AdminOrderController::class, 'api_store']);
Route::get('/admin-show-order/{id}', [AdminOrderController::class, 'api_show']);
Route::get('/admin-edit-order/{id}', [AdminOrderController::class, 'api_edit']);
Route::post('/admin-update-order/{order}', [AdminOrderController::class, 'api_update']);
Route::get('/admin-current-orders', [AdminOrderController::class, 'currentorders']);
Route::get('/admin-status-count', [AdminOrderController::class, 'getStatusCount']);
Route::get('/admin-latest-Pending-Orders', [AdminOrderController::class, 'latestPendingOrders']);
Route::post('/admin-order/draft', [AdminOrderController::class, 'saveDraft']);
Route::post('/admin-order/updatestatus/{id}', [AdminOrderController::class, 'updateOrderStatus']);
Route::get('/generate_next_order_no', [AdminOrderController::class, 'generate_next_order_no']);




//pending orders
Route::put('approve/status/{order}', [PendingOrderController::class, 'api_update']);
Route::get('pendingorders/fetch', [PendingOrderController::class, 'api_index']);


// Route::get('complete-orders', [OrderController::class, 'completeOrders']);
// Route::get('pending-orders', [OrderController::class, 'pendingOrders']);
// Route::get('draft-orders', [OrderController::class, 'draftOrders']);
// Route::get('approve-orders', [OrderController::class, 'approveOrders']);
// Route::get('unapprove-orders', [OrderController::class, 'unapproveOrders']);
// Route::get('cancel-orders', [OrderController::class, 'cancelOrders']);


// actor
Route::get('/fetch-actors', [ActorController::class, 'api_index']);
Route::post('/create-actor', [ActorController::class, 'api_store']);
Route::get('/show-actor/{id}', [ActorController::class, 'api_show']);
Route::get('/edit-actor/{id}', [ActorController::class, 'api_edit']);
Route::post('/update-actor/{actor}', [ActorController::class, 'api_update']);


// customer
Route::get('/fetch-customers', [CustomerController::class, 'api_index']);
Route::post('/create-customer', [CustomerController::class, 'api_store']);
Route::get('/show-customer/{id}', [CustomerController::class, 'api_show']);
Route::get('/edit-customer/{id}', [CustomerController::class, 'api_edit']);
Route::post('/update-customer/{partner}', [CustomerController::class, 'api_update']);
Route::delete('/delete-customer/{id}', [CustomerController::class, 'api_destroy']);




// partner

Route::post('/create-partner', [PartnerController::class, 'api_store']);
Route::get('/fetch-partner', [PartnerController::class, 'api_index']);
Route::get('/show-partner/{id}', [PartnerController::class, 'api_show']);
Route::get('/edit-partner/{id}', [PartnerController::class, 'api_edit']);
Route::post('/update-partner/{Partner}', [PartnerController::class, 'api_update']);

// Driver
Route::post('/create-driver', [DriverController::class, 'api_store']);
Route::get('/fetch-drivers', [DriverController::class, 'api_index']);
Route::get('/show-driver/{id}', [DriverController::class, 'api_show']);
Route::get('/edit-driver/{id}', [DriverController::class, 'api_edit']);
Route::post('/update-driver/{Partner}', [DriverController::class, 'api_update']);
Route::delete('/delete-driver/{id}', [DriverController::class, 'api_destroy']);



// driver assignment
Route::post('/assign-vehicle', [DriverAssignmentController::class, 'assign-vehicle']);
Route::post('/vehicle-assign', [DriverAssignmentController::class, 'api_vehicle_assign']);
Route::get('/incomplete-rides', [DriverAssignmentController::class, 'api_incompleteRides']);
Route::get('/getallrides/assigntodriver', [DriverAssignmentController::class, 'getallride_assign_to_driver']);
Route::post('/assign-vehicle', [DriverAssignmentController::class, 'assignVehicle']);
Route::get('/ride-list', [DriverAssignmentController::class, 'api_ridelist']);
Route::get('/ride-assign-driver-pending', [DriverAssignmentController::class, 'ride_assign_to_driver_pending']);
Route::get('/ride-assign-driver-complete', [DriverAssignmentController::class, 'ride_assign_to_driver_completed']);
Route::get('/ride-assign-driver-unapprove', [DriverAssignmentController::class, 'ride_assign_to_driver_unapproved']);
Route::get('/ride-assign-driver-inprogress', [DriverAssignmentController::class, 'ride_assign_to_driver_inprogress']);
Route::post('/ride-status-update-driver', [DriverAssignmentController::class, 'updateRideStatus']);
Route::get('/update-ride-status/{$rideId}', [DriverAssignmentController::class, 'update_ride_status']);
Route::post('/new-ride-request', [DriverAssignmentController::class, 'driver_order_create']);
Route::post('/driver-create-customer', [DriverAssignmentController::class, 'driver_create_customer']);
Route::get('/search-walkin', [DriverAssignmentController::class, 'getWalkinCustomers']);
Route::post('/new-ride-request-update/{order}', [DriverAssignmentController::class, 'driver_order_update']);
Route::get('/fetch-timelogs', [DriverAssignmentController::class, 'get_time_logs']);
Route::post('/store-timelogs', [DriverAssignmentController::class, 'store_time_logs']);


// Driver assignment module routes for admin
Route::get('/fetch-approved-rides', [DriverAssignmentController::class, 'api_approvedRides']);
Route::get('/fetch-incomplete-rides', [DriverAssignmentController::class, 'api_incompletedRides']);
Route::get('/fetch-complete-rides', [DriverAssignmentController::class, 'api_completedRides']);
Route::get('/fetch-cancel-rides', [DriverAssignmentController::class, 'api_cancelledRides']);





// toll-tax APIs
Route::post('/store-toll-tax',[TollTaxController::class,'api_store']);
Route::get('/fetch-all-toll-tax',[TollTaxController::class,'api_index']);
Route::get('/fetch-single-toll-tax/{id}',[TollTaxController::class,'api_show']);
Route::get('/edit-toll-tax/{id}',[TollTaxController::class,'api_edit']);
Route::put('/update-toll-tax/{id}',[TollTaxController::class,'api_update']);
Route::delete('/delete-toll-tax/{id}',[TollTaxController::class,'api_destroy']);

// fuel-expense APIs
Route::post('/store-fuel-expense',[FuelExpenseController::class,'api_store']);
Route::get('/fetch-all-fuel-expenses',[FuelExpenseController::class,'api_index']);
Route::get('/fetch-single-fuel-expense/{id}',[FuelExpenseController::class,'api_show']);
Route::get('/edit-fuel-expense/{id}',[FuelExpenseController::class,'api_edit']);
Route::put('/update-fuel-expense/{id}',[FuelExpenseController::class,'api_update']);
Route::delete('/delete-fuel-expense/{id}',[FuelExpenseController::class,'api_destroy']);

// timelog
// Route::get('/fetch-timelogs', [TimeLogController::class, 'get_time_logs']);

// vehicle class
Route::get('/get-vehicle-type',[VehicleClassController::class, 'api_index']);
Route::get('/get-vehicle-class', [VehicleClassController::class, 'api_vehicle_class']);
Route::post('/store-vehicle-class', [VehicleClassController::class, 'api_store']);



// vehicle
Route::get('/fetch-vehicles', [VehicleController::class, 'api_index']);
Route::get('/fetch/vehicle_class_id', [VehicleController::class, 'getVehicleClassIdByPartner']);
Route::get('/fetch/vehicle_details', [VehicleController::class, 'getVehicleDetailByPartner']);
Route::post('/create-vehicle', [VehicleController::class, 'api_store']);
Route::post('/update-vehicle/{vehicle}', [VehicleController::class, 'api_update']);
Route::delete('/delete-vehicle/{id}' ,[VehicleController::class ,'api_destroy']) ;
Route::get('/vehicle-manager', [VehicleController::class, 'api_vehicle_manager']);






// fetch city country

Route::get('fetch-country', [CityController::class, 'fetchCountry']);
Route::get('fetch-city/{country}', [CityController::class, 'fetchCity']);

// forget password
Route::post('password/email', [ForgotPasswordController::class, 'sendResetOtp']);
Route::post('password/reset', [ForgotPasswordController::class, 'resetPassword']);
Route::post('otp/verify', [ForgotPasswordController::class, 'verifyOtp']);


// Notification
// Route::post('/rides/{rideId}/status', [NotificationController::class, 'changeRideStatus']);
// Route::post('/test', [FirebaseController::class, 'test_firebase']);
Route::get('/get/notifications', [NotificationController::class, 'api_index']);
Route::post('notification/{id}/read', [NotificationController::class, 'markAsRead']);
Route::post('notification/all-read', [NotificationController::class, 'markAllAsRead']);
Route::get('/notification/count', [NotificationController::class, 'unreadCount']);



// Route::get('/send-notification', [NotificationController::class, 'sendPushNotification']);

Route::post('/notification-on-create-order', [NotificationController::class, 'notificationoncreateOrder']);
Route::post('/update-order-status-notification/{orderId}', [OrderController::class, 'updateOrderStatusnotification']);

// user guide
Route::post('/create-blog',[BlogController::class,'api_store']);
Route::post('/update-blog/{id}',[BlogController::class,'api_update']);
Route::get('/fetch-blog',[BlogController::class,'api_index']);


// Route::get('/blog', function() {
//     $blogs = Blog::all();

//     // Normalize the descriptions by removing newline characters and tabs, and decode escaped HTML characters
//     foreach ($blogs as $blog) {
//         $blog->description = str_replace('\t', ' ', $blog->description);
//         $blog->description = str_replace('\n', '', $blog->description);
//         $blog->description = str_replace('\r', '', $blog->description);
//         $blog->description = htmlspecialchars_decode($blog->description);
//     }

//     return response()->json(['blog' => $blogs]);
// });
// Route::get('/blog', function() {
//     $blogs = Blog::all();

//     // Clean the descriptions
//     foreach ($blogs as $blog) {
//         $blog->description = cleanDescription($blog->description);
//     }

//     return response()->json(['blog' => $blogs]);
// });

// business agent

Route::get('/fetch-business-agent', [BusinessAgentController::class, 'api_index']);
Route::post('/create-agent', [BusinessAgentController::class, 'api_store']);
Route::post('/update-agent/{partner}', [BusinessAgentController::class, 'api_update']);
Route::delete('/destroy-agent/{id}', [BusinessAgentController::class, 'api_destroy']);

// employee

Route::get('/fetch-employee', [EmployeeController::class, 'api_index']);
Route::post('/create-employee', [EmployeeController::class, 'api_store']);
Route::post('/update-employee/{partner}', [EmployeeController::class, 'api_update']);







// ledger
Route::get('/ledgers', [LedgerController::class, 'api_index']);
Route::get('/driver/ledgers', [LedgerController::class, 'driver_ledger']);

// locations:
Route::get('/locations', [LocationController::class, 'api_index']);
Route::post('/create-locations', [LocationController::class, 'api_store']);
Route::get('/show-locations/{id}', [LocationController::class, 'api_show']);
Route::post('/update-locations/{locations}', [LocationController::class, 'api_update']);
Route::delete('/locations/delete/{id}', [LocationController::class, 'api_destroy']);
// routes
Route::get('/routes/from', [RouteController::class, 'getFrom']);
Route::get('/routes/to', [RouteController::class, 'getTo']);
Route::any('/routes/rates', [RouteController::class, 'getRateList']);
Route::get('/fetch/routes', [RouteController::class, 'api_index']);
Route::post('/route/create', [RouteController::class, 'api_store']);
Route::post('/route/update/{route}', [RouteController::class, 'api_update']);
Route::delete('/route/delete/{id}', [RouteController::class, 'api_destroy']);

//countrycode
Route::get('/fetch/phonecode', [CountrycodeController::class, 'api_index']);

// active company
Route::get('/active-company', [CompanyController::class, 'api_activeCompany']);


// Route::post('/send/whatsapp', [WhatsAppController::class, 'sendNotification']);

// vehicle model

Route::get('/fetch/vehiclemodels',[VehicleModelController::class, 'api_index']);
Route::post('/create-vehicle-model', [VehicleModelController::class, 'api_store_vehicle_model']);


// vehicle company
Route::get('/fetch/vehicle/companies',[VehicleCompanyController::class, 'api_index']);
Route::post('/store/vehicle/company', [VehicleCompanyController::class, 'api_store']);



Route::get('fetch/today_total_rides',[OrderController::class,'api_today_total_rides']);
Route::get('fetch/today_active_rides',[OrderController::class,'api_today_active_rides']);
Route::get('fetch/today_pending_rides',[OrderController::class,'api_today_pending_rides']);
Route::get('fetch/monthly_pending_rides', [OrderController::class, 'api_monthly_pending_rides']);

Route::get('fetch/total_vehicles',[VehicleController::class,'api_total_vehicles']);
Route::get('fetch/total/driver/customer/vendor', [PartnerController::class, 'api_total_driver_customer_vendor']);

Route::get('fetch/vehicle_status',[VehicleController::class,'api_vehicle_status']);





// admin app routes:
Route::get('/fetch/incomplete_rides',[DriverAssignmentController::class, 'admin_index']);
Route::get('/fetch/available/vehicles',[DriverAssignmentController::class, 'getAvailableVehiclesAjax']);


// Load Type Routes
Route::get('/fetch/load_types',[LoadTypeController::class, 'api_index']);


// Unit Type Routes
Route::get('/fetch/unit_types',[UnitMeasureController::class, 'api_index']);
// Monthly Rental Vehicle Routes
 Route::get('/fetch/monthly/rental/vehicles',[MonthlyRentalVehicleController::class, 'api_index']);
 Route::post('/store/monthly/rental/vehicles',[MonthlyRentalVehicleController::class, 'api_store']);


//  Daily Rental Vehicle Routes
Route::get('/fetch/daily/rental/vehicles',[DailyRentalController::class, 'api_index']);
Route::post('/store/daily/rental/vehicles',[DailyRentalController::class, 'api_store']);


// Tour Routes Vehicle
Route::get('/fetch/tour/rental/vehicles',[TourServiceController::class, 'api_index']);
Route::post('/store/tour/rental/vehicles',[TourServiceController::class, 'api_store']);


// Account Type Routes
Route::get('/fetch/account_types',[AccountTypeController::class, 'api_index']);
Route::get('/fetch/account_sub_types/{id}',[AccountTypeController::class, 'api_accountSubTypes']);


// Account Routes
Route::post('/store/account',[AccountController::class, 'api_store']);
Route::get('/fetch/code_no/{account_type_id}/{account_subtype_id}',[AccountController::class, 'api_get_code_no']);



































