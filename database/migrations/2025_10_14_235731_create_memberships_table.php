<?php

use App\Enums\MembershipStatus;
use App\Models\Member;
use App\Models\Plan;
use App\Models\PlanType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('memberships', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Member::class)->constrained()->onDelete('restrict');
            $table->foreignIdFor(Plan::class)->constrained()->onDelete('restrict');
            $table->foreignIdFor(PlanType::class)->constrained()->onDelete('restrict');

            $table->enum('status', MembershipStatus::values())->default();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('memberships');
    }
};
