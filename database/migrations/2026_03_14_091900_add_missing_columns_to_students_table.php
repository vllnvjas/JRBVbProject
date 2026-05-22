<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('students', function (Blueprint $table) {
            if (! Schema::hasColumn('students', 'fname')) {
                $table->string('fname');
            }

            if (! Schema::hasColumn('students', 'mname')) {
                $table->string('mname');
            }

            if (! Schema::hasColumn('students', 'lname')) {
                $table->string('lname');
            }

            if (! Schema::hasColumn('students', 'email')) {
                $table->string('email');
            }

            if (! Schema::hasColumn('students', 'contactInfo')) {
                $table->string('contactInfo');
            }
        });
    }

    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            if (Schema::hasColumn('students', 'contactInfo')) {
                $table->dropColumn('contactInfo');
            }

            if (Schema::hasColumn('students', 'email')) {
                $table->dropColumn('email');
            }

            if (Schema::hasColumn('students', 'lname')) {
                $table->dropColumn('lname');
            }

            if (Schema::hasColumn('students', 'mname')) {
                $table->dropColumn('mname');
            }

            if (Schema::hasColumn('students', 'fname')) {
                $table->dropColumn('fname');
            }
        });
    }
};
