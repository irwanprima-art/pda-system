<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PdaHistoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SummaryDashboardController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MasterController;
use App\Models\AuditLog;

/*
|--------------------------------------------------------------------------
| WEB ROUTES
|--------------------------------------------------------------------------
| Operator, History, Monitor, Summary, Audit, Master
|--------------------------------------------------------------------------
*/

/* ==========================
| AUTH (LOGIN / LOGOUT)
========================== */
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth');

/* ==========================
| HOME ADMIN
========================== */
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/home', function () {
        return view('home');
    })->name('admin.home');
});

/* ==========================
| OPERATOR
========================== */
Route::middleware(['auth', 'role:operator,admin'])->group(function () {
    Route::get('/operator', function () {
        return view('operator');
    });
});

/* ==========================
| HISTORY PDA (ADMIN)
========================== */
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/', [PdaHistoryController::class, 'index']); // default
    Route::get('/history', [PdaHistoryController::class, 'index']);
    Route::get('/history/export', [PdaHistoryController::class, 'export']);
});

/* ==========================
| DASHBOARD TV
========================== */
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/dashboard-tv', [DashboardController::class, 'tv']);
});

/* ==========================
| DASHBOARD SUMMARY
========================== */
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/dashboard-summary', [SummaryDashboardController::class, 'index']);
});

/* ==========================
| AUDIT LOG
========================== */
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/audit-log', function () {
        $logs = AuditLog::latest()->limit(300)->get();
        return view('audit.index', compact('logs'));
    });
});

/* ==========================
| MASTER DATA (ADMIN)
========================== */
Route::middleware(['auth', 'role:admin'])->group(function () {

    /* ===== MASTER MENU ===== */
    Route::get('/master', [MasterController::class, 'index'])
        ->name('master.index');

    /* ===== MASTER EMPLOYEE ===== */
    Route::get('/master/employees', [MasterController::class, 'employees'])
        ->name('master.employees');

    Route::get('/master/employees/create', [MasterController::class, 'createEmployee'])
        ->name('master.employees.create');

    Route::post('/master/employees', [MasterController::class, 'storeEmployee'])
        ->name('master.employees.store');

    Route::get('/master/employees/{employee}/edit', [MasterController::class, 'editEmployee'])
        ->name('master.employees.edit');

    Route::put('/master/employees/{employee}', [MasterController::class, 'updateEmployee'])
        ->name('master.employees.update');

    /* ===== MASTER PDA ===== */
    Route::get('/master/pdas', [MasterController::class, 'pdas'])
        ->name('master.pdas');

    Route::get('/master/pdas/create', [MasterController::class, 'createPda'])
        ->name('master.pdas.create');

    Route::post('/master/pdas', [MasterController::class, 'storePda'])
        ->name('master.pdas.store');

    Route::get('/master/pdas/{pda}/edit', [MasterController::class, 'editPda'])
        ->name('master.pdas.edit');

    Route::put('/master/pdas/{pda}', [MasterController::class, 'updatePda'])
        ->name('master.pdas.update');
});
