<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\TypeDocumentController;
use App\Http\Controllers\ListAllowedUserController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\HeroDocumentController;
use App\Http\Controllers\LaporanController;
use Illuminate\Support\Facades\Route;


Route::middleware('guest')->group(function () {
    Route::get('/', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('login', [AuthenticatedSessionController::class, 'create'])
                ->name('login');

    Route::post('login', [AuthenticatedSessionController::class, 'store']);

    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])
                ->name('password.request');

    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])
                ->name('password.email');

    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])
                ->name('password.reset');

    Route::post('reset-password', [NewPasswordController::class, 'store'])
                ->name('password.store');

    Route::get('/register', [RegisteredUserController::class, 'getHalamanLogin'])->name('register-page');

    Route::post('/self-register', [RegisteredUserController::class, 'registerSelfUser'])->name('self-register');
    Route::get('/getdocument', [DocumentController::class, 'getDocument'])->name('getdocument');
    Route::get('/view-document-detail/{id}', [DocumentController::class, 'getDocumentDetail'])->name('document-detail');

//    Route::get('/document/{id}', [HeroDocumentController::class, 'getView'])->name('document.view');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        $data = [
            'active_sidebar' => [1,0]
        ];
        return view('dashboard', $data);
    })->name('admin-dashboard');

    Route::middleware('checkDocumentActive')->group(function () {
        Route::get('/user-settings-active', [UserController::class, 'getUserSettings'])->name('user-settings-active');
        Route::get('/user-settings-inactive', [UserController::class, 'getUserSettingsInactive'])->name('user-settings-inactive');

        Route::get('verify-email', EmailVerificationPromptController::class)
            ->name('verification.notice');

        Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
            ->middleware(['signed', 'throttle:6,1'])
            ->name('verification.verify');

        Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
            ->middleware('throttle:6,1')
            ->name('verification.send');

        Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])
            ->name('password.confirm');

        Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);

        Route::put('password', [PasswordController::class, 'update'])->name('password.update');

        Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
            ->name('logout');

        Route::post('/register', [RegisteredUserController::class, 'store'])->name('register');

        Route::delete('remove-user', [UserController::class, 'removeUser'])->name('remove-user');

        Route::get('/register-invitation', [RegisteredUserController::class, 'sendRegisterInvitationLink'])->name('register-invitation');

        Route::delete('/delete-invitation', [RegisteredUserController::class, 'deleteInvitation'])->name('delete-invitation');

        Route::delete('/clear-invitation', [RegisteredUserController::class, 'clearInvitation'])->name('clear-invitation');

        Route::post('/accept-register-request', [RegisteredUserController::class, 'acceptRegisterRequest'])->name('accept-register-request');
        Route::delete('/delete-register-request', [RegisteredUserController::class, 'deleteRegisterRequest'])->name('delete-register-request');

        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile');
//            Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');.update');
        Route::post('/edit-profile', [ProfileController::class, 'editProfile'])->name('edit-profile');

        Route::get('/change-profile-pict', [ProfileController::class, 'changeProfilePict'])->name('change-profile-pict');

        Route::post('/upload-profile-pict', [ProfileController::class, 'uploadProfilePict'])->name('uploadProfilePict');

        Route::post('/upload-file', [DocumentController::class, 'uploadFile'])->name('uploadFile');
        Route::post('/update-document/{id}', [DocumentController::class, 'updateDocument'])->name('updateDocument');
        Route::get('/document-add', [DocumentController::class, 'getDocumentManagementAdd'])->name('documentAdd');
        Route::get('/document/{id}/edit', [DocumentController::class, 'getDocumentManagementEdit'])->name('document.edit');
        Route::get('/hero/{id}/edit', [HeroDocumentController::class, 'edit'])->name('hero.edit');
        Route::put('/heroes/{id}', [HeroDocumentController::class, 'update'])->name('hero.update');
        Route::post('/laporan/add', [LaporanController::class, 'addLaporan'])->name('laporan.store');

        /**
         * Route ini digunakan untuk mendapatkan halaman user detail
         */
        Route::post("/user-detail", [UserController::class, 'getUserDetail'])->name('getUserDetail');
        Route::post("/user-detail-inactive", [UserController::class, 'getUserDetailInactive'])->name('getUserDetailInactive');

        /**
         * --------------DEPRECATED-----------------
         *
         * Method ini digunakan untuk mengembalikan akun yang sudah dinonaktifkan.
         */
        Route::post("/restore-account", [UserController::class, 'restoreAccount'])->name('restoreAccount');


        Route::delete('/remove-document', [DocumentController::class, 'removeDocument'])->name('remove-document');
        Route::post('upload-document-type', [TypeDocumentController::class, 'addDocumentType'])->name('uploadTypeDocument');


        Route::get('/list-allowed-user', [ListAllowedUserController::class, 'getListAllowedUser'])->name('list-allowed-user');
        Route::post('/upload-list-allowed-user', [ListAllowedUserController::class, 'uploadListAllowedUser'])->name('uploadListAllowedUser');
        Route::delete('/delete-list-allowed-user', [ListAllowedUserController::class, 'removeFromList'])->name('removeFromList');
        Route::post('/add-list-allowed-user', [ListAllowedUserController::class, 'addAllowedUser'])->name('addAllowedUser');

        Route::post('/change-password', [ProfileController::class, 'changePassword'])->name('change-password');

        Route::get('/role-management', [RoleController::class, 'getHalamanRoleManagement'])->name('role-management');
        Route::post('/add-role', [RoleController::class, 'addRole'])->name('addRole');
        Route::post('/edit-role', [RoleController::class, 'editRole'])->name('editRole');
        Route::delete('/remove-role', [RoleController::class, 'removeRole'])->name('removeRole');
        Route::post('/change-role-status', [RoleController::class, 'updateStatus'])->name('update-status');
    });
});
