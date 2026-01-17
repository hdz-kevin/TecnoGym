<?php

namespace Tests\Feature\Livewire;

use App\Livewire\Visits;
use App\Models\User;
use App\Models\Visit;
use App\Models\VisitType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;
use Carbon\Carbon;

class VisitsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_counts_visits_correctly()
    {
        $user = User::factory()->create();
        $visitType = VisitType::create(['name' => 'General', 'price' => 10]);

        // Today
        Visit::create([
            'visit_type_id' => $visitType->id,
            'visit_at' => Carbon::now(),
            'price_paid' => 10,
        ]);
        Visit::create([
            'visit_type_id' => $visitType->id,
            'visit_at' => Carbon::now(),
            'price_paid' => 10,
        ]);

        // Yesterday (Still this week/month typically, unless it's Monday 00:00 or 1st of month)
        // To be safe, let's pick a date that is definitely this week/month but NOT today.
        // Or specific dates.

        // Let's mock time to be "Wednesday, 15th of month"
        Carbon::setTestNow(Carbon::parse('2024-05-15 12:00:00'));

        // Create 2 visits Today (Wednesday)
        Visit::create(['visit_type_id' => $visitType->id, 'visit_at' => Carbon::now(), 'price_paid' => 10]);
        Visit::create(['visit_type_id' => $visitType->id, 'visit_at' => Carbon::now()->addHour(), 'price_paid' => 10]);

        // Create 1 visit Yesterday (Tuesday - same week, same month)
        Visit::create(['visit_type_id' => $visitType->id, 'visit_at' => Carbon::yesterday(), 'price_paid' => 10]);

        // Create 1 visit Last Week (but possibly same month? No, make sure it's same month if possible, or distinct)
        // Monday is 13th. Last week would be < 13th.
        // Let's do a visit on Monday (This week)
        $monday = Carbon::now()->startOfWeek();
        Visit::create(['visit_type_id' => $visitType->id, 'visit_at' => $monday, 'price_paid' => 10]);

        // Visit last week (e.g. 8 days ago)
        Visit::create(['visit_type_id' => $visitType->id, 'visit_at' => Carbon::now()->subDays(8), 'price_paid' => 10]);

        // Visit last month
        Visit::create(['visit_type_id' => $visitType->id, 'visit_at' => Carbon::now()->subMonth(), 'price_paid' => 10]);


        // Expected counts:
        // Today (Wed): 2
        // This Week (Mon-Sun): 2 (Wed) + 1 (Tue) + 1 (Mon) = 4.
        // Wait, startOfWeek defaults? Laravel usually Monday.
        // Wed is 15th. Tue is 14th. Mon is 13th. All in same week.
        // Last week visit (8 days ago -> 7th May). Not this week.
        // This Month (May): 2 (Today) + 1 (Yesterday) + 1 (Monday) + 1 (8 days ago, 7th May - same month) = 5.
        // Last month (April): Not counted.

        Livewire::test(Visits::class)
            // ->assertSee('2') // Today
            // ->assertSee('4') // Week
            // ->assertSee('5'); // Month
            ->assertSet('visitsToday', 2)
            ->assertSet('visitsThisWeek', 4)
            ->assertSet('visitsThisMonth', 5);
    }
}
