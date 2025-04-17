<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\JobController;
use App\Http\Controllers\Api\EventController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\DashbaordController;
use App\Http\Controllers\Api\CardController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\ResumeController;
use App\Http\Controllers\Api\ReviewController;
use App\Http\Controllers\Api\ChatController;
use App\Http\Controllers\Api\NotificationController;

Route::GET('terms-and-conditions', [DashbaordController::class,'terms_and_conditions']);
Route::GET('privacy-policy', [DashbaordController::class,'privacy_policy']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['middleware' => ['auth:sanctum']], function () {

    Route::controller(ChatController::class)->group(function () {
        Route::GET('getChats', 'getChats');
        Route::GET('getChatMessages', 'getChatMessages');
        Route::POST('chatFileUpload', 'chatFileUpload');
        Route::GET('available-users', 'available_users');
        Route::GET('block-user','block_user');
        Route::GET('unblock-user','unblock_user');
        Route::GET('unblock-all','unblock_all');
        Route::GET('blocked-users','blocked_users');
    });

    Route::controller(DashbaordController::class)->group(function () {
        Route::GET('dashboard', 'dashboard');
      
        Route::GET('about-us', 'about_us');
        Route::GET('united-capitalism', 'united_capitalism');
        Route::GET('how-to-be-hustler', 'how_to_be_hustler');
    });

    Route::controller(CardController::class)->group(function () {
        Route::POST('add-card', 'add_card');
        Route::GET('cards', 'cards');
        Route::POST('set-default-card', 'set_default_card');
        Route::GET('plans', "plans");
    });

    Route::controller(CartController::class)->group(function () {
        Route::POST('add-cart', 'add_cart');
        Route::PUT('update-cart', 'update_cart');
        Route::GET('view-cart', 'view_cart');
        Route::POST('place-order', 'place_order');
        Route::GET('order-detail', 'order_detail');
        Route::PUT('update-address', 'update_address');
    });

    Route::controller(ProductController::class)->group(function () {
        Route::POST('add-product', 'addProduct');
        Route::GET('edit-product', 'editProduct');
        Route::POST('update-product', 'update_product');
        Route::GET('delete-product', 'deleteProduct');
        Route::GET('view-favourite', 'view_favourite');
        Route::GET('products', 'products');
        Route::GET('product-detail', 'product_detail');
        Route::GET('view-shop', 'view_shop');
        Route::POST('edit-shop', 'edit_shop');
        Route::GET('your-shop', 'your_shop');
    });

    Route::controller(JobController::class)->group(function () {
        Route::POST('add-job', 'add_job');
        Route::GET('job-detail', 'job_detail');
        Route::POST('apply-for-job', 'apply_for_job');
        Route::GET('job-requests', 'job_requests');
        Route::PUT('update-job-request-status', 'update_job_request_status');
        Route::GET('wanted-jobs', 'wanted_jobs');
        Route::PUT('update-job-status', 'update_job_status');
        Route::GET('my-jobs', 'my_jobs');
        Route::GET('edit-job', 'edit_job');
        Route::POST('update-job', 'update_job');
        Route::GET('user-jobs', 'user_jobs');

    });

    Route::controller(EventController::class)->group(function () {

        Route::GET('my-events', 'my_events');
        Route::POST('add-event', 'add_event');
        Route::GET('edit-event', 'edit_event');
        Route::POST('update-event', 'update_event');
        Route::GET('event-detail', 'event_detail');
        Route::GET('interested-in-event', 'interested_in_event');
        Route::GET('attendees', 'attendees');
        Route::PUT('update-attendees-status', 'update_attendees_status');
        Route::GET('view-profile', 'view_profile');
        Route::GET('user-events', 'user_events');
        Route::GET('events', 'events');
        Route::POST('add-to-favourite', 'add_to_favourite');
        Route::POST('remove-from-favourite', 'remove_from_favourite');


    });

    Route::controller(ProfileController::class)->group(function () {
        Route::GET('online-user-profile', 'online_user_profile');
        Route::PUT('is-notification', 'is_notification');
        Route::GET('your-profile', 'your_profile');
        Route::PUT('profile-setup', 'profile_setup');
        Route::POST('add-portfolio', 'add_portfolio');
        Route::DELETE('delete-portfolio', 'delete_portfolio');
        Route::GET('portfolio-listing', 'portfolio_listing');
        Route::POST('add-certifications', 'add_certifications');
        Route::DELETE('delete-certification', 'delete_certification');
        Route::POST('add-employer-history', 'add_employer_history');
        Route::PUT('edit-employer-history', 'edit_employer_history');
        Route::PUT('edit-profile', 'editProfile');

        // Route::GET('privacy-policy', 'privacy_policy');
        // Route::GET('terms', 'terms');
    });

    Route::controller(ReviewController::class)->group(function () {
        Route::POST('add-review', 'add_review');
        Route::POST('reviews', 'index');
    });

    Route::controller(ResumeController::class)->group(function () {
        Route::GET('resume', 'getResume');
        Route::POST('update-resume', 'updateResume');
        Route::GET('delete-resume', 'deleteResume');
    });
  
    Route::controller(NotificationController::class)->group(function () {
        Route::PUT('is-notification', 'is_notification');
        Route::GET('notifications', 'notifications');
        Route::PUT('is-read', 'is_read_notification');
        Route::PUT('is-online', 'is_online');
    });

    Route::controller(AuthController::class)->group(function () {
        Route::PUT('update-fcm-token', 'updateFcmToken');
        Route::PUT('update-user-location', 'updateUserLocation');
        Route::POST('change-password', 'changePassword');
        Route::POST('logout', 'logout');
    });

});


Route::prefix('auth')->group(function() {

    Route::controller(AuthController::class)->group(function () {
        Route::POST('register', 'register');
        Route::POST('verify-token', 'verifyToken');
        Route::POST('resend-otp-token', 'resendOtpToken');
        Route::PUT('set-password', 'setPassword');
        Route::post('social-login', 'socialLogin');
        Route::POST('login', 'login');
        Route::POST('forgot-password', 'forgotPassword');
        Route::POST('reset-password', 'resetPassword');
        Route::GET('unauthenticated', 'unauthenticatedUser')->name('api.unauthenticated');
    });
});
