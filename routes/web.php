<?php

use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers\Frontend\CategoryController;
use App\Http\Controllers\Frontend\ContactController;
use App\Http\Controllers\Frontend\Dashboard\NotificationController;
use App\Http\Controllers\Frontend\Dashboard\ProfileController;
use App\Http\Controllers\Frontend\Dashboard\SettingController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\NewSebscriberController;
use App\Http\Controllers\Frontend\PostController;
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


Route::group(['as' => 'frontend.'], function () {

    // Home  ////////////////////////////////////////////////////
    Route::get('/', [HomeController::class, 'index'])->name('home');

    // Subscribe to newsletter  ////////////////////////////////////
    Route::post('/subscribe', [NewSebscriberController::class, 'subscribe'])->name('new.subscribe');

    //   Category With Posts  //////////////////////////////////////////////////
    Route::get('/category/{slug}', CategoryController::class)->name('category.posts');

    // Route  Post //////////////////////////////////////////////////////////////////////////////
    Route::controller(PostController::class)->prefix('post')->name('post.')->group(function () {
        Route::get('/search',  'search')->name('search');
        Route::get('/{slug}', 'show')->name('show');
        Route::get('/more/comment/{slug}', 'allComment')->name('all.comment');
        Route::get('/less/comment/{slug}', 'lessComment')->name('less.comment');
        Route::post('/store/comment', 'storeComment')->name('comment.store');
    });
    /////////////////////////////////////////////////////////////////////////////////////////////

    // Route Contact //////////////////////////////////////////////////////////////////////////////
    Route::controller(ContactController::class)->prefix('contact')->name('contact.')->group(function () {
        Route::get('/show', [ContactController::class, 'show'])->name('show');
        Route::post('/store', [ContactController::class, 'store'])->name('store');
    });
    ////////////////////////////////////////////////////////////////////////////////////////////

    // Route Dashboard user //////////////////////////////////////////////////////////////////////////////
    Route::prefix('account')->name('dashboard.')->middleware('auth','verified','checkUserStatus')->group(function () {
      Route::controller(ProfileController::class)->group(function () {
          Route::get('/profile', 'index')->name('profile');
          Route::post('/post/store', 'storePost')->name('post.store');
          Route::delete('/post/delete/{id}', 'deletePost')->name('post.delete');
          Route::post('/post/restore', 'restorePost')->name('post.restore');
          Route::get('/post/comments/{id}', 'postComments')->name('post.comments');
          Route::get('/post/edit/{id}', 'editPost')->name('post.edit');
          Route::post('/post/update', 'updatePost')->name('post.update');
          Route::post('/post/image/delete', 'deletePostImage')->name('post.remove_image');
        });
        Route::controller(SettingController::class)->group(function () {
            Route::get('/setting', 'index')->name('setting');
            Route::put('/update/setting', 'updateSetting')->name('setting.update');
            // Route::post('/update/password', 'updatePassword')->name('password.update');
        });
        Route::controller(NotificationController::class)->group(function () {
            Route::get('/notification', 'index')->name('notification');
            Route::post('/notification/mark-all-as-read', 'markAllAsRead')->name('notification.mark_all_as_read');
            Route::delete('/notification/delete/{id}', 'deleteNotification')->name('notification.delete');
            Route::post('/notification/delete-all', 'deleteAllNotifications')->name('notification.delete_all');

        });




    });

        Route::get('wait' , function(){
          return view('frontend.wait');
    })->name('wait');
    ///////////////////////////////////////////////////////////////////////////////////////////////////////

    // login and register
    Route::get('frontend/login', function () {
        return view('frontend.auth.login');
    })->name('login')->middleware('guest');
    Route::get('frontend/register', function () {
        return view('frontend.auth.register');
    })->name('register')->middleware('guest');

    Route::get('frontend/forgot-password', function () {
        return view('frontend.auth.passwords.email');
    })->name('forgot.password')->middleware('guest');
});

Route::get('email/verify', [VerificationController::class, 'show'])->name('verification.notice');
Route::get('email/verify/{id}/{hash}', [VerificationController::class, 'verify'])->name('verification.verify');
Route::post('email/resend', [VerificationController::class, 'resend'])->name('verification.resend');
Auth::routes();


Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
