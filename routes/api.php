<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\AdminController;


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

// Registration and Authentication API
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

// Resource API
Route::middleware('auth:api')->prefix('v1')->group(function () {
    // Customer API
    Route::controller(LoanController::class)->group(function () {
        Route::get('loans/{loanId}', 'showLoan');
        Route::post('loans', 'createLoan');
        Route::patch('repayments/{id}', 'addRepayment');
    });

    // Loan API
    Route::post('admin/loans/{id}/approve', [AdminController::class, 'approveLoan'])->middleware('isAdmin');
});
