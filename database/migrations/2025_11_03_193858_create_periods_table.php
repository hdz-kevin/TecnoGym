<?php

use App\Models\Duration;
use App\Models\Membership;
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
        Schema::create('periods', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Membership::class)->constrained()->onDelete('restrict');
            $table->foreignIdFor(Duration::class)->constrained()->onDelete('restrict');
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->integer('price_paid');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('periods');
    }
};
