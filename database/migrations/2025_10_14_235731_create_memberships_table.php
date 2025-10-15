<?php

use App\Enums\MembershipStatus;
use App\Models\Member;
use App\Models\MembershipType;
use App\Models\Period;
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
            $table->foreignIdFor(MembershipType::class)->constrained()->onDelete('restrict');
            $table->foreignIdFor(Period::class)->constrained()->onDelete('restrict');

            $table->integer('price');
            $table->date('start_date');
            $table->date('end_date');
            $table->enum('status', MembershipStatus::values())->default(MembershipStatus::ACTIVE->value);
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
