<?php

use App\Http\Controllers\Web\AuthController;
use App\Http\Controllers\Web\BeneficiaryController;
use App\Http\Controllers\Web\CardController;
use App\Http\Controllers\Web\DashboardController;
use App\Http\Controllers\Web\KycController;
use App\Http\Controllers\Web\NotificationController;
use App\Http\Controllers\Web\ProfileController;
use App\Http\Controllers\Web\TransferController;
use App\Http\Controllers\Web\PasswordResetController;
use App\Http\Controllers\Web\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Web\Admin\ExchangeRateController as AdminExchangeRate;
use App\Http\Controllers\Web\Admin\KycController as AdminKyc;
use App\Http\Controllers\Web\Admin\SettingsController as AdminSettings;
use App\Http\Controllers\Web\Admin\TransactionController as AdminTransaction;
use App\Http\Controllers\Web\Admin\UserController as AdminUser;
use Illuminate\Support\Facades\Route;

// ─── Public routes (no auth required) ────────────────────────────────────────
Route::get('/confidentialite', fn() => view('legal.privacy'))->name('privacy');

// ─── Guest routes ─────────────────────────────────────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('/login',     [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login',    [AuthController::class, 'login']);
    Route::get('/register',  [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);

    // Password reset
    Route::get('/mot-de-passe/oublier',         [PasswordResetController::class, 'showForgotForm'])->name('password.request');
    Route::post('/mot-de-passe/email',           [PasswordResetController::class, 'sendResetLink'])->name('password.email');
    Route::get('/mot-de-passe/reset/{token}',   [PasswordResetController::class, 'showResetForm'])->name('password.reset');
    Route::post('/mot-de-passe/reset',           [PasswordResetController::class, 'resetPassword'])->name('password.update');
});

// ─── Authenticated routes ─────────────────────────────────────────────────────
Route::middleware('auth')->group(function () {

    Route::get('/otp',         [AuthController::class, 'showOtp'])->name('otp.show');
    Route::post('/otp/verify', [AuthController::class, 'verifyOtp'])->name('otp.verify');
    Route::post('/otp/resend', [AuthController::class, 'resendOtp'])->name('otp.resend');
    Route::post('/logout',     [AuthController::class, 'logout'])->name('logout');

    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Transfers
    Route::prefix('transfers')->name('transfers.')->group(function () {
        Route::get('/',                  [TransferController::class, 'index'])->name('index');
        Route::get('/nouveau',           [TransferController::class, 'create'])->name('create');
        Route::get('/{uuid}',            [TransferController::class, 'show'])->name('show');
        Route::patch('/{uuid}/cancel',   [TransferController::class, 'cancel'])->name('cancel');
    });

    // Beneficiaries
    Route::resource('beneficiaries', BeneficiaryController::class)->except(['show']);

    // Cards
    Route::prefix('cartes')->name('cards.')->group(function () {
        Route::get('/',                  [CardController::class, 'index'])->name('index');
        Route::post('/',                 [CardController::class, 'store'])->name('store');
        Route::patch('/{card}/default',  [CardController::class, 'setDefault'])->name('setDefault');
        Route::delete('/{card}',         [CardController::class, 'destroy'])->name('destroy');
    });

    // KYC
    Route::prefix('kyc')->name('kyc.')->group(function () {
        Route::get('/',        [KycController::class, 'index'])->name('index');
        Route::post('/upload', [KycController::class, 'upload'])->name('upload');
    });

    // Profile
    Route::prefix('profil')->name('profile.')->group(function () {
        Route::get('/',               [ProfileController::class, 'index'])->name('index');
        Route::patch('/',             [ProfileController::class, 'update'])->name('update');
        Route::patch('/photo',        [ProfileController::class, 'updatePhoto'])->name('updatePhoto');
        Route::patch('/mot-de-passe', [ProfileController::class, 'changePassword'])->name('changePassword');
        Route::patch('/pin',          [ProfileController::class, 'updatePin'])->name('updatePin');
    });

    // Notifications
    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/',           [NotificationController::class, 'index'])->name('index');
        Route::post('/{id}/read', [NotificationController::class, 'markRead'])->name('markRead');
        Route::post('/read-all',  [NotificationController::class, 'markAllRead'])->name('markAllRead');
    });

    Route::get('/support/nouveau', fn() => view('support.create'))->name('support.create');
});

// ─── Admin routes ─────────────────────────────────────────────────────────────
Route::middleware(['auth', 'can:admin-access'])->prefix('admin')->name('admin.')->group(function () {

    Route::get('/', [AdminDashboard::class, 'index'])->name('dashboard');

    Route::prefix('transactions')->name('transactions.')->group(function () {
        Route::get('/',                           [AdminTransaction::class, 'index'])->name('index');
        Route::get('/{uuid}',                     [AdminTransaction::class, 'show'])->name('show');
        Route::patch('/{transaction}/compliance', [AdminTransaction::class, 'updateCompliance'])->name('compliance');
    });

    Route::prefix('utilisateurs')->name('users.')->group(function () {
        Route::get('/',               [AdminUser::class, 'index'])->name('index');
        Route::get('/{uuid}',         [AdminUser::class, 'show'])->name('show');
        Route::patch('/{user}/status',[AdminUser::class, 'updateStatus'])->name('status');
    });

    Route::prefix('kyc')->name('kyc.')->group(function () {
        Route::get('/',             [AdminKyc::class, 'index'])->name('index');
        Route::patch('/{document}', [AdminKyc::class, 'update'])->name('update');
    });

    Route::prefix('taux-de-change')->name('exchange-rates.')->group(function () {
        Route::get('/',               [AdminExchangeRate::class, 'index'])->name('index');
        Route::post('/',              [AdminExchangeRate::class, 'store'])->name('store');
        Route::delete('/{exchangeRate}', [AdminExchangeRate::class, 'destroy'])->name('destroy');
    });

    Route::prefix('parametres')->name('settings.')->group(function () {
        Route::get('/',   [AdminSettings::class, 'index'])->name('index');
        Route::patch('/', [AdminSettings::class, 'update'])->name('update');
    });
});
