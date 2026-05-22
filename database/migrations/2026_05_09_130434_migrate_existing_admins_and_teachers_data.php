<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Copy existing admins from user_accounts to admins table
        $admins = DB::table('user_accounts')->where('role', 'admin')->get();
        foreach ($admins as $admin) {
            DB::table('admins')->insert([
                'user_account_id' => $admin->id,
                'created_at' => $admin->created_at,
                'updated_at' => $admin->updated_at,
            ]);
        }

        // Copy existing teachers from user_accounts to teachers table
        $teachers = DB::table('user_accounts')->where('role', 'teacher')->get();
        foreach ($teachers as $teacher) {
            DB::table('teachers')->insert([
                'fname' => explode(' ', trim($teacher->name ?? ''))[0] ?? '',
                'mname' => null,
                'lname' => trim(str_replace(explode(' ', trim($teacher->name ?? ''))[0] ?? '', '', trim($teacher->name ?? ''))) ?? '',
                'degree_id' => null,
                'contactInfo' => null,
                'user_account_id' => $teacher->id,
                'created_at' => $teacher->created_at,
                'updated_at' => $teacher->updated_at,
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Clear admins and teachers tables
        DB::table('admins')->truncate();
        DB::table('teachers')->truncate();
    }
};
