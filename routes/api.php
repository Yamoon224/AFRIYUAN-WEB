<?php

use App\Http\Controllers\Api\V1\CardController;
use App\Http\Controllers\Api\V1\StripeWebhookController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| AfriYuan API Routes — v1
|--------------------------------------------------------------------------
*/

// ── Stripe Webhook (no auth — Stripe signs the payload) ──────────────────────
Route::post('/v1/stripe/webhook', [StripeWebhookController::class, 'handle'])
    ->name('stripe.webhook');

// ── Public routes ─────────────────────────────────────────────────────────────
Route::prefix('v1')->group(function () {

    // Auth
    Route::prefix('auth')->group(function () {
        Route::post('/register',    'App\Http\Controllers\Api\V1\AuthController@register');
        Route::post('/login',       'App\Http\Controllers\Api\V1\AuthController@login');
        Route::post('/verify-otp',  'App\Http\Controllers\Api\V1\AuthController@verifyOtp');
        Route::post('/refresh',     'App\Http\Controllers\Api\V1\AuthController@refresh');
    });

    // Lookups (public)
    Route::get('/countries',   'App\Http\Controllers\Api\V1\CountryController@index');
    Route::get('/currencies',  'App\Http\Controllers\Api\V1\CurrencyController@index');
    Route::get('/exchange-rates/{from}/{to}', 'App\Http\Controllers\Api\V1\ExchangeRateController@show');
});

// ── Protected routes (Sanctum) ────────────────────────────────────────────────
Route::prefix('v1')->middleware('auth:sanctum')->group(function () {

    // Auth
    Route::post('/auth/logout', 'App\Http\Controllers\Api\V1\AuthController@logout');
    Route::get('/auth/me',      'App\Http\Controllers\Api\V1\AuthController@me');

    // KYC
    Route::prefix('kyc')->group(function () {
        Route::get('/status',   'App\Http\Controllers\Api\V1\KycController@status');
        Route::post('/upload',  'App\Http\Controllers\Api\V1\KycController@upload');
    });

    // Cards (Visa / Mastercard)
    Route::prefix('cards')->group(function () {
        Route::get('/',                         [CardController::class, 'index']);
        Route::post('/setup-intent',            [CardController::class, 'setupIntent']);
        Route::post('/',                        [CardController::class, 'store']);
        Route::patch('/{card}/set-default',     [CardController::class, 'setDefault']);
        Route::delete('/{card}',                [CardController::class, 'destroy']);
    });

    // Transfers
    Route::prefix('transfers')->group(function () {
        Route::post('/quote',   'App\Http\Controllers\Api\V1\TransferController@quote');
        Route::post('/',        'App\Http\Controllers\Api\V1\TransferController@store');
        Route::get('/',         'App\Http\Controllers\Api\V1\TransferController@index');
        Route::get('/{uuid}',   'App\Http\Controllers\Api\V1\TransferController@show');
    });

    // Stripe (setup + payment intent creation)
    Route::prefix('stripe')->group(function () {
        Route::post('/payment-intent', 'App\Http\Controllers\Api\V1\StripeController@createPaymentIntent');
    });

    // Beneficiaries
    Route::apiResource('beneficiaries', 'App\Http\Controllers\Api\V1\BeneficiaryController');

    // Mobile money
    Route::apiResource('mobile-money', 'App\Http\Controllers\Api\V1\MobileMoneyController');

    // Notifications
    Route::prefix('notifications')->group(function () {
        Route::get('/',                         'App\Http\Controllers\Api\V1\NotificationController@index');
        Route::patch('/{id}/read',              'App\Http\Controllers\Api\V1\NotificationController@markRead');
        Route::post('/mark-all-read',           'App\Http\Controllers\Api\V1\NotificationController@markAllRead');
    });

    // Profile
    Route::prefix('profile')->group(function () {
        Route::put('/',         'App\Http\Controllers\Api\V1\ProfileController@update');
        Route::post('/photo',   'App\Http\Controllers\Api\V1\ProfileController@updatePhoto');
        Route::put('/pin',      'App\Http\Controllers\Api\V1\ProfileController@updatePin');
    });

    // Wallet
    Route::prefix('wallet')->group(function () {
        Route::get('/',              'App\Http\Controllers\Api\V1\WalletController@show');
        Route::get('/transactions',  'App\Http\Controllers\Api\V1\WalletController@transactions');
        Route::post('/topup',        'App\Http\Controllers\Api\V1\WalletController@topUp');
        Route::post('/withdraw',     'App\Http\Controllers\Api\V1\WalletController@withdraw');
    });

    // Internal transfers
    Route::prefix('internal-transfers')->group(function () {
        Route::get('/',                 'App\Http\Controllers\Api\V1\InternalTransferController@index');
        Route::post('/',                'App\Http\Controllers\Api\V1\InternalTransferController@store');
        Route::get('/search-recipient', 'App\Http\Controllers\Api\V1\InternalTransferController@searchRecipient');
    });
});
