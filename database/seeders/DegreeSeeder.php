<?php

namespace Database\Seeders;

use App\Models\Degree;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DegreeSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $degrees = [
            'BS Computer Science',
            'BS Information Technology',
            'BS Information Systems',
            'BS Business Administration',
            'BS Education',
            'BA Communication',
        ];

        foreach ($degrees as $name) {
            Degree::updateOrCreate(['name' => $name], ['name' => $name]);
        }
    }
}
