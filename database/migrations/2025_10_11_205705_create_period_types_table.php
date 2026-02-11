<?php

use App\Enums\DurationUnit;
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
        Schema::create('period_types', function (Blueprint $table) {
            $table->id();
            $table->foreignId('membership_type_id')->constrained('membership_types', 'id')->onDelete('restrict');

            $table->string('name');
            $table->integer('duration_value');
            $table->enum('duration_unit', DurationUnit::values());
            $table->integer('duration_in_days'); // For easier ordering
            $table->integer('price');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('period_types');
    }
};
