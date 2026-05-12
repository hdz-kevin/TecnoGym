<?php

use App\Livewire\Dashboard;
use App\Livewire\Members\Members;
use App\Livewire\Memberships\Memberships;
use App\Livewire\Visits;
use App\Livewire\Memberships\Prices;
use App\Livewire\VerifyCode;
use App\Livewire\Store\Products;
use App\Livewire\Store\Sales;
use Illuminate\Support\Facades\Route;

Route::middleware(['guest'])->group(function () {
    Route::redirect('/', 'dashboard');
    Route::get('/dashboard', Dashboard::class)->name('dashboard');
    Route::get('/memberships', Memberships::class)->name('memberships.index');
    Route::get('/members', Members::class)->name('members.index');
    Route::get('/visits', Visits::class)->name('visits.index');
    Route::get('/memberships/prices', Prices::class)->name('memberships.prices');
    Route::get('/verify-code', VerifyCode::class)->name('verify-code');
    Route::get('/products', Products::class)->name('products.index');
    Route::get('/sales', Sales::class)->name('sales.index');
});
