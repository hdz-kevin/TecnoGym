<?php

namespace App\Actions;

use App\Enums\MembershipStatus;
use App\Enums\MemberStatus;
use App\Enums\PeriodStatus;
use App\Models\Member;
use App\Models\Membership;
use App\Models\Period;

class SyncStatuses
{
    /**
     * Sync all statuses (periods → memberships → members) based on current date.
     */
    public function handle(): int
    {
        $updatedPeriods = $this->syncPeriods();
        $updatedMemberships = $this->syncMemberships();
        $updatedMembers = $this->syncMembers();

        return $updatedMemberships;
    }

    /**
     * Mark periods as COMPLETED when their end_date has passed.
     */
    private function syncPeriods(): int
    {
        return Period::where('status', PeriodStatus::IN_PROGRESS)
            ->where('end_date', '<', now())
            ->update(['status' => PeriodStatus::COMPLETED]);
    }

    /**
     * Mark memberships as EXPIRED when all their periods are COMPLETED.
     */
    private function syncMemberships(): int
    {
        $count = 0;

        Membership::where('status', MembershipStatus::ACTIVE)
            ->with('periods')
            ->each(function (Membership $membership) use (&$count) {
                $hasActivePeriod = $membership->periods->contains('status', PeriodStatus::IN_PROGRESS);

                if (! $hasActivePeriod) {
                    $membership->update(['status' => MembershipStatus::EXPIRED]);
                    $count++;
                }
            });

        return $count;
    }

    /**
     * Mark members as EXPIRED when all their memberships are EXPIRED.
     */
    private function syncMembers(): int
    {
        $count = 0;

        Member::where('status', MemberStatus::ACTIVE)
            ->with('memberships')
            ->each(function (Member $member) use (&$count) {
                $hasActiveMemberships = $member->memberships->contains('status', MembershipStatus::ACTIVE);

                if (! $hasActiveMemberships) {
                    $member->update(['status' => MemberStatus::EXPIRED]);
                    $count++;
                }
            });

        return $count;
    }
}
