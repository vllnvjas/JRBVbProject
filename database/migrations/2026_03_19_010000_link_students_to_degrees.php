<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('students', 'degree_id')) {
            Schema::table('students', function (Blueprint $table) {
                $table->foreignId('degree_id')->nullable()->after('email')->constrained('degrees')->nullOnDelete();
            });
        }

        if (Schema::hasColumn('students', 'degree')) {
            $distinctDegrees = DB::table('students')
                ->whereNotNull('degree')
                ->where('degree', '!=', '')
                ->distinct()
                ->pluck('degree');

            foreach ($distinctDegrees as $degreeName) {
                DB::table('degrees')->updateOrInsert(
                    ['name' => $degreeName],
                    ['created_at' => now(), 'updated_at' => now()]
                );
            }

            $students = DB::table('students')
                ->select('id', 'degree')
                ->whereNotNull('degree')
                ->where('degree', '!=', '')
                ->get();

            foreach ($students as $student) {
                $degreeId = DB::table('degrees')->where('name', $student->degree)->value('id');

                if ($degreeId) {
                    DB::table('students')
                        ->where('id', $student->id)
                        ->update(['degree_id' => $degreeId]);
                }
            }

            Schema::table('students', function (Blueprint $table) {
                $table->dropColumn('degree');
            });
        }
    }

    public function down(): void
    {
        if (! Schema::hasColumn('students', 'degree')) {
            Schema::table('students', function (Blueprint $table) {
                $table->string('degree')->nullable()->after('email');
            });
        }

        if (Schema::hasColumn('students', 'degree_id')) {
            $students = DB::table('students')
                ->select('id', 'degree_id')
                ->whereNotNull('degree_id')
                ->get();

            foreach ($students as $student) {
                $degreeName = DB::table('degrees')->where('id', $student->degree_id)->value('name');

                DB::table('students')
                    ->where('id', $student->id)
                    ->update(['degree' => $degreeName]);
            }

            Schema::table('students', function (Blueprint $table) {
                $table->dropConstrainedForeignId('degree_id');
            });
        }
    }
};
