<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\IncomeController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ReportController;

Route::get('/', fn() => redirect('/dashboard'));

// Dashboard
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// Members
Route::get('/members', [MemberController::class, 'index'])->name('members.index');
Route::post('/members', [MemberController::class, 'store'])->name('members.store');
Route::put('/members/{member}', [MemberController::class, 'update'])->name('members.update');
Route::patch('/members/{member}/toggle-status', [MemberController::class, 'toggleStatus'])->name('members.toggleStatus');
Route::delete('/members/{member}', [MemberController::class, 'destroy'])->name('members.destroy');

// Income
Route::get('/income', [IncomeController::class, 'index'])->name('income.index');
Route::post('/income', [IncomeController::class, 'store'])->name('income.store');
Route::put('/income/{income}', [IncomeController::class, 'update'])->name('income.update');
Route::delete('/income/{income}', [IncomeController::class, 'destroy'])->name('income.destroy');

// Expenses
Route::get('/expenses', [ExpenseController::class, 'index'])->name('expenses.index');
Route::post('/expenses', [ExpenseController::class, 'store'])->name('expenses.store');
Route::put('/expenses/{expense}', [ExpenseController::class, 'update'])->name('expenses.update');
Route::delete('/expenses/{expense}', [ExpenseController::class, 'destroy'])->name('expenses.destroy');
Route::post('/expenses/analyze-image', [ExpenseController::class, 'analyzeImage'])->name('expenses.analyzeImage');

// Invoices
Route::get('/invoice', [InvoiceController::class, 'index'])->name('invoice.index');
Route::get('/invoice/{invoice}', [InvoiceController::class, 'show'])->name('invoice.show');
Route::get('/invoice/{invoice}/pdf', [InvoiceController::class, 'downloadPdf'])->name('invoice.pdf');

// Reports
Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
Route::post('/reports/ask-ai', [ReportController::class, 'askAI'])->name('reports.askAI');
