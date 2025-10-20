<?php

use App\Enums\MembershipStatus;
use App\Models\Membership;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::call(function () {
    Membership::whereDate('end_date', '<=', now())
                ->where('status', MembershipStatus::ACTIVE->value)
                ->update(['status' => MembershipStatus::EXPIRED->value]);
})
    ->daily()
    ->timezone(config('app.timezone'));
