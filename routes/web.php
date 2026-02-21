<?php

use App\Livewire\Members;
use App\Livewire\Memberships;
use App\Livewire\Prices\Memberships as PricesMemberships;
use App\Livewire\Prices\Visits as PricesVisits;
use App\Livewire\VerifyMembership;
use App\Livewire\Visits;
use Illuminate\Support\Facades\Route;

Route::middleware(['guest'])->group(function () {
    Route::redirect('/', 'dashboard');
    Route::view('dashboard', 'dashboard')->name('dashboard');
    Route::get('members', Members::class)->name('members.index');
    Route::get('prices/memberships', PricesMemberships::class)->name('prices.memberships');
    Route::get('prices/visits', PricesVisits::class)->name('prices.visits');
    Route::get('memberships', Memberships::class)->name('memberships.index');
    Route::get('visits', Visits::class)->name('visits.index');
    Route::get('verify-membership', VerifyMembership::class)->name('verify-membership');
});
