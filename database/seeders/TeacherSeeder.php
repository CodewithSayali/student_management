<?php

namespace Database\Seeders;

use App\Models\Teacher;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TeacherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        Teacher::create(['teacher_name' => 'Seema Parab']);
        Teacher::create(['teacher_name' => 'Vardha Parab']);
    }
}
