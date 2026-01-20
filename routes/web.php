<?php

use App\Livewire\Members;
use App\Livewire\Memberships;
use App\Livewire\Prices;
use App\Livewire\VerifyMembership;
use App\Livewire\Visits;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => view('welcome'))->name('home');

Route::view('dashboard', 'dashboard')
     ->middleware(['guest'])
     ->name('dashboard');

Route::middleware(['guest'])->group(function () {
    Route::get('members', Members::class)->name('members.index');
    Route::get('prices', Prices::class)->name('prices.index');
    Route::get('memberships', Memberships::class)->name('memberships.index');
    Route::get('visits', Visits::class)->name('visits.index');
    Route::get('verify-membership', VerifyMembership::class)->name('verify-membership');
});
