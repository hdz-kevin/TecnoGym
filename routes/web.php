<?php

use App\Livewire\Members;
use App\Livewire\Memberships;
use App\Livewire\Prices;
use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use App\Livewire\VerifyMembership;
use App\Livewire\Visits;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => view('welcome'))->name('home');

Route::view('dashboard', 'dashboard')
     ->middleware(['auth', 'verified'])
     ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Route::get('settings/profile', Profile::class)->name('settings.profile');
    Route::get('settings/password', Password::class)->name('settings.password');
    Route::get('settings/appearance', Appearance::class)->name('settings.appearance');

    Route::get('members', Members::class)->name('members.index');
    Route::get('prices', Prices::class)->name('prices.index');
    Route::get('memberships', Memberships::class)->name('memberships.index');
    Route::get('visits', Visits::class)->name('visits.index');
    Route::get('verify-membership', VerifyMembership::class)->name('verify-membership');
});

require __DIR__.'/auth.php';
