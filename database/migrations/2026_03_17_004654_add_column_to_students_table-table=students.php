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
        if (Schema::hasTable('degrees')) {
            Schema::table('degrees', function (Blueprint $table) {
                // No-op migration kept for compatibility.
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('degrees')) {
            Schema::table('degrees', function (Blueprint $table) {
                // No-op rollback.
            });
        }
    }
};
