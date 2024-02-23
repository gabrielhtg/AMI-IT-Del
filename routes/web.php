<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\NewsController;
use App\Models\User;
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

Route::get('/', function () {
    return view('dashboard');
})->name('dashboard');

Route::get('/document-management', [\App\Http\Controllers\DocumentController::class, 'getDocumentManagementView'])->name('documentManagement');

Route::get('/dashboard', function () {

    return view('dashboard');
})->middleware(['auth', 'verified']);

// Route::get('/admindashboard', function () {

//     return view('admindashboard');
// })->middleware(['auth', 'verified'])->name('admindashboard');

Route::middleware('auth')->group(function () {

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/user-settings', [UserController::class, 'getUserSettings'])->name('user-settings');
    Route::get('/announcement', [AnnouncementController::class, 'getAnnouncement'])->name('announcement');
    Route::post('/announcement', [AnnouncementController::class, 'store'])->name('announcement.add');
    Route::get('/announcement/detail/{id}', [AnnouncementController::class, 'getDetail'])->name('announcement.detail');
    Route::post('/updateannouncement/{id}', [AnnouncementController::class, 'updateannouncement'])->name('updateannouncement');
    Route::get('/deleteannouncement/detail/{id}', [AnnouncementController::class, 'deleteannouncement'])->name('deleteannouncement');
    Route::get('/news', [NewsController::class, 'getNews'])->name('news');
    Route::post('/addnews', [NewsController::class, 'store'])->name('newsadd');
    Route::get('/news/detail/{id}', [NewsController::class, 'getDetail'])->name('news.detail');
    Route::post('updatenews/{id}',[NewsController::class, 'updatenews'])->name('updatenews');
    Route::get('/deletenews/detail/{id}', [NewsController::class, 'deletenews'])->name('deleteannews');
    
});

require __DIR__ . '/auth.php';
