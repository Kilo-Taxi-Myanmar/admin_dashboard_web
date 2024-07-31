<?php

use App\Http\Controllers\Api\AdminDashboardController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\configController;
use App\Http\Controllers\Api\CustromerTripController;
use App\Http\Controllers\Api\DriverLocationController;
use App\Http\Controllers\Api\FeeController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\SystemController;
use App\Http\Controllers\Api\DriverSearchController;
use App\Http\Controllers\Api\ForgetPasswordController;
use App\Http\Controllers\Api\TransactionController;
use App\Http\Controllers\Api\TripController;
use App\Http\Controllers\Api\TripStatusController;
use App\Http\Controllers\Backend\ChangeLogController;
use App\Http\Controllers\Backend\CustomNotificationController;
use Illuminate\Support\Facades\Route;
use Spatie\Permission\Contracts\Role;
use App\Models\User;
use Illuminate\Support\Facades\Broadcast;

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

Route::post('/login', [AuthController::class, 'login']);
Route::post('customer/login', [AuthController::class, 'customerLogin']);

Route::post('/register', [AuthController::class, 'register']);
Route::post('/sign-up', [AuthController::class, 'register']);
Route::post('/customer/register',[AuthController::class, 'cusregister']);
Route::get('/app-link', [ChangeLogController::class, 'appLink']);
Route::middleware(['auth:sanctum'])->group(function () {
    // our routes to be protected will go in here
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::post('refresh', [AuthController::class, 'refresh']);


    //routes for user
    Route::get('/user/profile', [UserController::class, 'profile']);
    Route::post('/user/update', [UserController::class, 'update']);
    Route::post('/customer/update', [UserController::class, 'cusupdate']);

    Route::delete('/user/{user}', [UserController::class, 'destroy']);
    Route::get('/user/delete/{user}',[UserController::class,'disable']);
    Route::get('user/trip', [UserController::class, 'userTrip']);//user trip history

    Route::get('user/notifications', [UserController::class, 'userNoti']);





    //routes for transactions
    Route::get('/user/transactions', [TransactionController::class, 'index']);
    Route::get('/transactions/{id}', [TransactionController::class, 'show']);
    Route::get('/notification', [NotificationController::class, 'index'])->name('api.notification');
    Route::post('/notification', [NotificationController::class, 'store']);

    //routes for balance,initial-fee,commission-fee

    Route::get('/initial-fee', [SystemController::class, 'getInitialFee']);
    Route::get('/normal-fee', [SystemController::class, 'getNormalFee']);
    Route::get('/commission-fee', [SystemController::class, 'getCommissionFee']);
    Route::get('/waiting-fee', [SystemController::class, 'getWaitingFee']);

    Route::post('/store-token', [CustomNotificationController::class, 'storeToken']);
    Route::get('/custom-notification', [NotificationController::class, 'custom_notification']);



    // driver route
    Route::get('driver/all', [DriverLocationController::class, 'driverall']);
    Route::post('driver/update/{id}', [DriverLocationController::class, 'driverupdate']);
    Route::get('extra-fee',[TripStatusController::class,'extraFee']);
    Route::post('driver/available/update', [DriverLocationController::class, 'driverAvailableUpdate']);
    Route::get('search/trip/{id}',[DriverSearchController::class,'searchTripId']);
    Route::post('/driver/trip/start/{id}',[TripStatusController::class,'start']); //start trip
    Route::get('/total/price-and-trip/{range}',[UserController::class,'totalTripandPrice']);//total trip count and price


    // customer route
    Route::get('/car/type',[DriverLocationController::class,'cartype']);
    Route::get('/fee', [FeeController::class, 'index']);
    Route::apiResource('/trip', TripController::class, array("as" => "api")); //trip post  end  get
    Route::post('/trip/start', [TripController::class, 'tripStart']);
    Route::post('/near-driver-all',[DriverLocationController::class,'neardriverall']);

    Route::post('/trip/driver/update/{id}',[CustromerTripController::class,'tripindriverid']);
    Route::get('available/driver/all', [DriverLocationController::class, 'driverallcustomer']);



    // booking
    // Route::get('customer/trip/data',[CustromerTripController::class,'index']);
    // Route::apiResource('/customer/trip', CustromerTripController::class, array("as" => "api"));//trip create and trip get custromer
    Route::post('customer/trip/update/{id}',[CustromerTripController::class,'update']);
    Route::get('driver/search/trip/{id}',[DriverSearchController::class,'searchTrip']);
    Route::post('customer/booking',[CustromerTripController::class,'store']);
    Route::get('/customer/active-booking',[CustromerTripController::class,'activeBooking']);

    // driver and customer
    Route::post('trip/status/update/{id}',[TripController::class,'tripStatusupdate']);
    Route::post('change/password',[ForgetPasswordController::class,'changePassword']);
    Route::get('/top/lists',[UserController::class,'topLists']);




//   admin dashboard 

     
Route::get('/wallet/driverlist',[AdminDashboardController::class,'showDriver']);
Route::post('/wallet/topup',[AdminDashboardController::class,'topUp']);
Route::get('/pending/list',[AdminDashboardController::class,'pendingList']);
Route::post('/status/change',[AdminDashboardController::class,'changeActiveStatus']);
Route::get('/trips/all/history',[AdminDashboardController::class,'tripsAllHistory']);














    // test
    Route::get('driver/search',[DriverSearchController::class,'searcTripnearhDriver']);
    Route::post('driver/cancel/search/driver',[DriverSearchController::class,'drivercancleanotherdriver']);
    Route::post('/driver/trip/end/{id}',[TripStatusController::class,'end']);
    Route::get('/driver/trip/cash/{id}',[TripStatusController::class,'cash']);



});


    // customer route
    Route::get('/get-fee', [SystemController::class, 'getFee']);
    //send otp customer and forget password
    Route::post('/verify/otp',[AuthController::class,'verifyOtp']);
    Route::post('/send-otp', [AuthController::class, 'sendOTP']);

    // forget password
    Route::post('forget/password',[ForgetPasswordController::class,'forgetPassword']);

    Route::post('/check/phone',[AuthController::class,'checkphone']);


    Route::get('/config',[configController::class,'config']);
    Route::post('/config/add',[configController::class,'configstore']);



