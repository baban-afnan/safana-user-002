<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\WalletController;
use App\Http\Controllers\TransferController;
use App\Http\Controllers\Admin\UserManagementController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'verified', 'admin'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // User Profile Routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/photo', [ProfileController::class, 'updatePhoto'])->name('profile.photo');
    Route::post('/profile/pin', [ProfileController::class, 'updatePin'])->name('profile.pin');
    Route::post('/profile/update-required', [ProfileController::class, 'updateRequired'])->name('profile.updateRequired');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // General User Routes
    Route::get('/transactions', [App\Http\Controllers\TransactionController::class, 'index'])->name('transactions');
    Route::get('/support', [App\Http\Controllers\SupportController::class, 'index'])->name('support');

    // Wallet Routes
    Route::prefix('wallet')->group(function () {
        Route::get('/', [WalletController::class, 'index'])->name('wallet');
        Route::post('/create', [WalletController::class, 'createWallet'])->name('wallet.create');
        Route::post('/claim-bonus', [WalletController::class, 'claimBonus'])->name('wallet.claimBonus');
        
        // P2P Transfer Routes
        Route::get('/transfer', [TransferController::class, 'index'])->name('wallet.transfer');
        Route::post('/transfer/verify', [TransferController::class, 'verifyUser'])->name('transfer.verify');
        Route::post('/transfer/process', [TransferController::class, 'processTransfer'])->name('transfer.process');
        Route::post('/transfer/verify-pin', [TransferController::class, 'verifyPin'])->name('verify.pin');
    });

    // User Management Routes
    Route::prefix('users')->name('admin.users.')->group(function () {
        Route::get('/', [UserManagementController::class, 'index'])->name('index');
        Route::post('/', [UserManagementController::class, 'store'])->name('store');
        Route::post('/block-ip', [UserManagementController::class, 'blockIp'])->name('block-ip');
        Route::delete('/unblock-ip/{blockedIp}', [UserManagementController::class, 'unblockIp'])->name('unblock-ip');
        Route::get('/download-sample', [UserManagementController::class, 'downloadSample'])->name('download-sample');
        Route::post('/import', [UserManagementController::class, 'import'])->name('import');
        Route::get('/{user}', [UserManagementController::class, 'show'])->name('show');
        Route::get('/{user}/edit', [UserManagementController::class, 'edit'])->name('edit');
        Route::put('/{user}', [UserManagementController::class, 'update'])->name('update');
        Route::delete('/{user}', [UserManagementController::class, 'destroy'])->name('destroy');
        Route::patch('/{user}/status', [UserManagementController::class, 'updateStatus'])->name('update-status');
        Route::patch('/{user}/role', [UserManagementController::class, 'updateRole'])->name('update-role');
        Route::patch('/{user}/limit', [UserManagementController::class, 'updateLimit'])->name('update-limit');
        Route::patch('/{user}/verify-email', [UserManagementController::class, 'verifyEmail'])->name('verify-email');
    });

    // Admin Wallet Management
    Route::prefix('admin/wallet')->name('admin.wallet.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\AdminWalletController::class, 'index'])->name('index');
        Route::get('/fund', [\App\Http\Controllers\Admin\AdminWalletController::class, 'fundView'])->name('fund.view');
        Route::post('/fund', [\App\Http\Controllers\Admin\AdminWalletController::class, 'fund'])->name('fund');
        Route::get('/bulk-fund', [\App\Http\Controllers\Admin\AdminWalletController::class, 'bulkFundView'])->name('bulk-fund.view');
        Route::post('/bulk-fund', [\App\Http\Controllers\Admin\AdminWalletController::class, 'bulkFund'])->name('bulk-fund');
    });

    // Service Management
    Route::prefix('admin/services')->name('admin.services.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\ServiceController::class, 'index'])->name('index');
        Route::post('/', [\App\Http\Controllers\Admin\ServiceController::class, 'store'])->name('store');
        Route::get('/{service}', [\App\Http\Controllers\Admin\ServiceController::class, 'show'])->name('show');
        Route::put('/{service}', [\App\Http\Controllers\Admin\ServiceController::class, 'update'])->name('update');
        Route::delete('/{service}', [\App\Http\Controllers\Admin\ServiceController::class, 'destroy'])->name('destroy');

        // Field Routes
        Route::post('/{service}/fields', [\App\Http\Controllers\Admin\ServiceController::class, 'storeField'])->name('fields.store');
        Route::put('/fields/{field}', [\App\Http\Controllers\Admin\ServiceController::class, 'updateField'])->name('fields.update');
        Route::delete('/fields/{field}', [\App\Http\Controllers\Admin\ServiceController::class, 'destroyField'])->name('fields.destroy');

        // Price Routes
        Route::post('/{service}/prices', [\App\Http\Controllers\Admin\ServiceController::class, 'storePrice'])->name('prices.store');
        Route::put('/prices/{price}', [\App\Http\Controllers\Admin\ServiceController::class, 'updatePrice'])->name('prices.update');
        Route::delete('/prices/{price}', [\App\Http\Controllers\Admin\ServiceController::class, 'destroyPrice'])->name('prices.destroy');
    });

    // Data Variation Management
    Route::prefix('admin/data-variations')->name('admin.data-variations.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\DataVariationController::class, 'index'])->name('index');
        Route::get('/{service}', [\App\Http\Controllers\Admin\DataVariationController::class, 'show'])->name('show');
        Route::post('/', [\App\Http\Controllers\Admin\DataVariationController::class, 'store'])->name('store');
        Route::put('/{dataVariation}', [\App\Http\Controllers\Admin\DataVariationController::class, 'update'])->name('update');
        Route::delete('/{dataVariation}', [\App\Http\Controllers\Admin\DataVariationController::class, 'destroy'])->name('destroy');
    });
});

    

require __DIR__.'/auth.php';
