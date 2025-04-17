<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\EventController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\JobController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\BannerController;

Route::patch('/fcm-token', [AdminController::class, 'updateToken'])->name('fcmToken');
Route::get('test-socket', function(){
    return view('test_socket');
});

Route::get('/', function () {
    return redirect('login');
});


Route::match(['get', 'post'], 'delete-account-request', [AdminController::class, 'deleteAccountRequest'])->name('admin.userLogin');

Route::get('logout', function (){
    auth()->logout();
    return redirect('login');
})->name('admin.logout');

Route::get('privacy-policy', function() {
    return view('privacy');
});
Route::match(['get', 'post'], 'login', [AdminController::class, 'login'])->name('admin.login');
Route::get('/dashboard', [AdminController::class, 'account_request'])->name('admin.userDashboard');
// Route::match(['get', 'post'], 'account-request', [AdminController::class, 'deleteAccountReq'])->name('admin.accRequest');
Route::get('/account-request', [AdminController::class, 'deleteAccountReq'])->name('admin.accRequest');

Route::prefix('admin')->middleware('admin')->name('admin.')->group(function () {

    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/delete-data-request', [AdminController::class, 'deleteAccountRequests'])->name('deleteAccountRequests');
    Route::get('/delete-account/{id}', [AdminController::class, 'deleteAccount'])->name('deleteAccount');

    

    Route::resource('banners', BannerController::class);
    Route::get('users', [UserController::class, 'index'])->name('users');
    Route::get('user-detail/{id}', [UserController::class, 'user_detail'])->name('userDetail');
    Route::get('user-wise-products/{id}', [UserController::class, 'user_wise_products'])->name('userDetail');
    Route::get('/jobs', [JobController::class, function () {
        return view('admin.jobs.index');
    }]);
    Route::get('/userJobs', [JobController::class, 'userJobs'])->name('userJobs');
    Route::get('/jobRequest', [JobController::class, 'jobRequest'])->name('jobRequest');
    Route::get('/jobReviews', [JobController::class, 'jobReviews'])->name('jobReviews');
    Route::get('/job-detail/{id}', [JobController::class, 'jobDetail'])->name('jobDetail');

    Route::get('/events', [EventController::class, function () {
        return view('admin.events.index');
    }]);
    Route::get('/all-events', [EventController::class, 'events'])->name('events');
    Route::get('/event-detail/{id}', [EventController::class, 'eventDetail'])->name('eventDetail');
    Route::get('/eventUsers', [EventController::class, 'eventUsers'])->name('eventUsers');

    Route::get('/products', [EventController::class, function () {
        return view('admin.products.index');
    }]);
    Route::get('/all-products', [ProductController::class, 'products'])->name('products');
    Route::get('/product-detail/{id}', [ProductController::class, 'productDetail'])->name('productDetail');
    Route::get('/productOrders', [ProductController::class, 'productOrders'])->name('productOrders');

    Route::get('/services', function () {
        return view('admin.services.index');
    });
    Route::get('/service-detail/{id}', [ProductController::class, 'serviceDetail'])->name('serviceDetail');

    Route::get('/orders', function () {
        return view('admin.orders.index');
    });
    Route::get('/allOrders', [OrderController::class, 'allOrders'])->name('allOrders');
    Route::get('/order-detail/{id}', [OrderController::class, 'orderDetail'])->name('orderDetail');

    Route::get('/pdf_file', function () {
        return view('admin.pdf_file');
    });
    Route::get('pdf', [AdminController::class, 'viewFile'])->name('pdf');
    Route::post('pdf_file', [AdminController::class, 'pdfFile'])->name('pdf_file');

    Route::match(['get', 'post'], '/privacy-policy', [AdminController::class, 'privacy_policy'])->name('privacy_policy');
    Route::match(['get', 'post'], '/united-capitalism', [AdminController::class, 'united_capitalism'])->name('united_capitalism');
    Route::match(['get', 'post'], '/terms-and-conditions', [AdminController::class, 'terms_and_conditions'])->name('terms_and_conditions');
    Route::match(['get', 'post'], '/about-us', [AdminController::class, 'about_us'])->name('about_us');
    
    Route::match(['get', 'post'], '/setting', [AdminController::class, 'setting'])->name('setting');
    Route::get('/change-password', [AdminController::class, 'changePassword'])->name('changePassword');
    Route::post('/update-admin-password', [AdminController::class, 'updateAdminPassword'])->name('updateAdminPassword');
    
});

Route::get('test-notification', [AdminController::class, 'testNotification']);


