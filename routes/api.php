<?php

use App\Http\Controllers\Api\Account\Notification\NotificationController;
use App\Http\Controllers\Api\Account\PostController;
use App\Http\Controllers\Api\Account\SettingController as AccountSettingController;
use App\Http\Controllers\Api\Auth\ForgotPasswordController;
use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\Auth\RegisterController;
use App\Http\Controllers\Api\Auth\RestPasswordController;
use App\Http\Controllers\Api\Auth\VerifyEmailController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ContactController;
use App\Http\Controllers\Api\GeneralController;
use App\Http\Controllers\Api\SettingController;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Mockery\Matcher\Not;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/



Route::middleware('auth:sanctum','checkUserStatus','throttle:user_api','checkUserEmail')->group(function () {
    Route::get('/user/account', function (Request $request) {
        return api_response('User retrieved successfully', 200, new UserResource($request->user()));
    });

    //********************************  Update user account  ********************************/
    Route::put('/user/update/account/{id}', [AccountSettingController::class, 'updateUser']);


    //**********************          Posts                          */
    Route::get('/user/account/get_post', [PostController::class, 'getPost']);
    Route::get('/user/account/show/{slug}/post', [PostController::class, 'showPost']);
    Route::post('/user/account/store_post', [PostController::class, 'storePost']);
    Route::put('/user/account/update_post/{slug}', [PostController::class, 'updatePost']);
    Route::delete('/user/account/delete_post/{id}', [PostController::class, 'deletePost']);

    //**********************          Commments                       */
    Route::get('/user/account/post_comments/{slug}', [PostController::class, 'postComments']);
    Route::post('/user/account/post_comment', [PostController::class, 'storeComment']);

    Route::get('/user/account/notifications', [NotificationController::class, 'getNotifications']);
    Route::get('/user/account/notification/read/{id}', [NotificationController::class, 'readNotification']);

});

// *******************   Auth Routes ************************************//
Route::prefix('user')->group(function () {

Route::post('login', [LoginController::class, 'login']);
Route::delete('logout', [LoginController::class, 'logout'])->middleware('auth:sanctum');
Route::post('register', [RegisterController::class, 'register'])->middleware('throttle:register');

Route::post('forgot-password', [ForgotPasswordController::class, 'forgotPassword'])->middleware('throttle:password');
Route::post('reset-password', [RestPasswordController::class, 'resetPassword'])->middleware('throttle:password');
});

Route::post('verify/email', [VerifyEmailController::class, 'verifyEmail'])->middleware('auth:sanctum');
Route::get('rest/code', [VerifyEmailController::class, 'RestCodeOtp'])->middleware('auth:sanctum');
////////////////////////////////////////////////////////////////////////////



//**********************************  Get General Posts **********************************//
Route::controller(GeneralController::class)->group(function () {

    Route::get('/all/posts', 'allPosts')->name('api.posts.all');
    Route::get('/search', 'searchPosts')->name('api.posts.search');
    Route::get('/show/{slug}/post/', 'showPosts')->name('api.posts.show');
    Route::get('/comments/{slug}/post/', 'postComments')->name('api.posts.comments');
});
////////////////////////////////////////////////////////////////////////////////////////////


//***************  Get Categories   **********************************//
Route::controller(CategoryController::class)->group(function () {
    Route::get('/all/categories', 'allCategories');
    Route::get('/category/{slug}/posts', 'categoryPosts');
});
//////////////////////////////////////////////////////////////////////////

//**************** Contact Us ****************************************//
Route::post('/contact/store', [ContactController::class, 'contactStore'])->middleware('throttle:contact');
////////////////////////////////////////////////////////////////////////

//******************* Setting *****************************************//
Route::controller(SettingController::class)->group(function () {

    Route::get('settings', 'getSettings');
    Route::get('related/sites', 'relateSite');
});
/////////////////////////////////////////////////////////////////////////
