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
        Schema::create('teachers', function (Blueprint $table) {
            $table->id();
            $table->string('fname');
            $table->string('mname')->nullable();
            $table->string('lname');
            $table->unsignedBigInteger('degree_id')->nullable();
            $table->string('contactInfo')->nullable();
            $table->unsignedBigInteger('user_account_id')->nullable();
            $table->timestamps();
            $table->foreign('user_account_id')->references('id')->on('user_accounts')->onDelete('cascade');
            $table->foreign('degree_id')->references('id')->on('degrees')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teachers');
    }
};
