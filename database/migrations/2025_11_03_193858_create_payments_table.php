<?php

use App\Enums\PaymentStatus;
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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Membership::class)->constrained()->onDelete('restrict');

            $table->date('start_date');
            $table->date('end_date');
            $table->integer('price_paid');
            $table->enum('status', PaymentStatus::values())->default(PaymentStatus::IN_PROGRESS->value);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
