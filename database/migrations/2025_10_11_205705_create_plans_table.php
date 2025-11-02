<?php

use App\Enums\DurationUnit;
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
        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(PlanType::class)->constrained()->onDelete('restrict');

            $table->string('name');
            $table->integer('duration_value');
            $table->enum('duration_unit', DurationUnit::values()); // day, week, month
            $table->integer('price');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plans');
    }
};
