<?php

use App\Http\Controllers\DispatcherController;
use App\Http\Controllers\MasterController;
use App\Http\Controllers\RepairRequestController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return view('welcome');
});

// авторизация пользователя
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.action');

// создание заявок
Route::prefix('requests')->group(function () {
    Route::get('/create', [RepairRequestController::class, 'create'])->name('requests.create');
    Route::post('/', [RepairRequestController::class, 'store'])->name('requests.store');
});

// для аутентифицированных пользователей
Route::middleware(['auth'])->group(function () {
    // выход из системы
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // для диспетчера
    Route::prefix('dispatcher')->middleware('role:dispatcher')->group(function () {
        Route::get('/', [DispatcherController::class, 'index'])->name('dispatcher.index');
        Route::get('/{repairRequest}', [DispatcherController::class, 'show'])->name('dispatcher.show');

        Route::patch('/{repairRequest}/assign', [DispatcherController::class, 'assignMaster'])->name('dispatcher.assign');
        Route::patch('/{repairRequest}/cancel', [DispatcherController::class, 'cancelRequest'])->name('dispatcher.cancel');
    });

    // для мастера
    Route::prefix('master')->middleware('role:master')->group(function () {
        Route::get('/', [MasterController::class, 'index'])->name('master.index');
        Route::get('/{repairRequest}', [MasterController::class, 'show'])->name('master.show');

        Route::patch('/{repairRequest}/take', [MasterController::class, 'takeRequest'])->name('master.take');
        Route::patch('/{repairRequest}/complete', [MasterController::class, 'completeRequest'])->name('master.complete');
    });
});

