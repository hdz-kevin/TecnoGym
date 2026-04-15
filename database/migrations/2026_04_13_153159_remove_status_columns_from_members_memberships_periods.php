<?php

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
        Schema::table('members', function (Blueprint $table) {
            if (Schema::hasColumn('members', 'status')) {
                $table->dropColumn('status');
            }
        });

        Schema::table('memberships', function (Blueprint $table) {
            if (Schema::hasColumn('memberships', 'status')) {
                $table->dropColumn('status');
            }
        });

        Schema::table('periods', function (Blueprint $table) {
            if (Schema::hasColumn('periods', 'status')) {
                $table->dropColumn('status');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('members', function (Blueprint $table) {
            $table->string('status')->default('3')->after('name');
        });

        Schema::table('memberships', function (Blueprint $table) {
            $table->string('status')->default('1')->after('membership_type_id');
        });

        Schema::table('periods', function (Blueprint $table) {
            $table->string('status')->default('in_progress')->after('end_date');
        });
    }
};
