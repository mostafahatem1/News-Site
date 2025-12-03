<?php

use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers\Backend\AdminController;
use App\Http\Controllers\Backend\Auth\ForgetPasswordController;
use App\Http\Controllers\Backend\Auth\LoginController;
use App\Http\Controllers\Backend\Authorization\AuthorizationController;
use App\Http\Controllers\Backend\Category\CategoryController;
use App\Http\Controllers\Backend\Contact\ContactController as ContactContactController;
use App\Http\Controllers\Backend\HomeController;
use App\Http\Controllers\Backend\Post\PostController;
use App\Http\Controllers\Backend\Setting\SettingController;
use App\Http\Controllers\Backend\User\UserController;
use App\Http\Controllers\Backend\ContactController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;














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


Route::group(['prefix' => 'admin', 'as' => 'admin.'], function () {
    //////////// This route group is for the admin login and forgot_password //////////////////////////
    Route::group(['middleware' => ['guest:admin']], function () {
        Route::get('/login', [LoginController::class, 'loginForm'])->name('login');
        Route::post('/login', [LoginController::class, 'login'])->name('login.submit');
        Route::controller(ForgetPasswordController::class)->group(function () {
             Route::get('/forgot_password',  'showLinkRequestForm')->name('forgot_password');
            Route::post('/forgot_password',  'sendOtpEmail')->name('forgot_password.submit');
            Route::get('/forgot_password_code{email}',  'showCodeForm')->name('forgot_password_code');
            Route::post('/forgot_password_code',  'verifyCode')->name('verify-code');
            Route::get('/reset_password_from/{email}',  'showResetForm')->name('reset_password.form');
            Route::post('/reset_password',  'resetPassword')->name('reset_password.submit');
        });
    });
    ////////////////////////////////////////////////////////////////////////////////////////////////////

    Route::group(['middleware' => ['auth:admin']], function () {
         Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

         /// This route group is for the admin dashboard //////////////////////////
         Route::get('index', HomeController::class)->name('backend.index');
         /////////////////////////////////////////////////////////////////////////

         // user management routes///////////////
         Route::resource('users',UserController::class);
        ///////////////////////////////////////////////////////////////////////

        // Category management routes////////////
        Route::resource('categories',CategoryController::class);
        Route::get('categories/status/{id}', [CategoryController::class, 'status'])->name('categories.status');
        ///////////////////////////////////////////////////////////////////////

        // Post management routes////////////
        Route::resource('posts', PostController::class);
        Route::get('posts/status/{id}', [PostController::class, 'status'])->name('posts.status');
        /////////////////////////////////////////////////////////////////////

        // Settings routes//////////////////
        Route::get('settings', [SettingController::class, 'settings'])->name('settings');
        Route::post('settings', [SettingController::class, 'update'])->name('settings.update');
        ///////////////////////////////////////////////////////////////////////

        // contact routes////////////////////////////////
        Route::get('contact', [ContactContactController::class, 'contact'])->name('contact');
        Route::get('contact/show/{id}', [ContactContactController::class, 'show'])->name('contact.show');
        Route::delete('contact/{id}', [ContactContactController::class, 'destroy'])->name('contact.destroy');
        Route::post('contact', [ContactContactController::class, 'store'])->name('contact.store');

        ////////////////////////////////////////////////////////////////////////


        // Admin management routes//////////////////
        Route::resource('admin', AdminController::class);
        /////////////////////////////////////////////////////////////////

        // Authorization routes ///////////////////////////////////////
        Route::resource('authorizations', AuthorizationController::class);
        ///////////////////////////////////////////////////////////////////

    });
});


Route::get('email/verify', [VerificationController::class, 'show'])->name('verification.notice');
Route::get('email/verify/{id}/{hash}', [VerificationController::class, 'verify'])->name('verification.verify');
Route::post('email/resend', [VerificationController::class, 'resend'])->name('verification.resend');
Auth::routes();
