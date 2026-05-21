<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Master\CustomerList;
use App\Livewire\Master\ProductList;
use App\Livewire\Transaction\DoList;
use App\Livewire\Transaction\DoForm;
use App\Livewire\Transaction\InvoiceList;

Route::redirect('/', '/login');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');

    // Master Data
    Route::get('/customers', CustomerList::class)->name('customers.index');
    Route::get('/products', ProductList::class)->name('products.index');

    // Transactions
    Route::get('/delivery-orders', DoList::class)->name('do.index');
    Route::get('/delivery-orders/create', DoForm::class)->name('do.create');
    Route::get('/delivery-orders/{id}/edit', DoForm::class)->name('do.edit');
    Route::get('/invoices', InvoiceList::class)->name('invoices.index');
});

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__.'/auth.php';
