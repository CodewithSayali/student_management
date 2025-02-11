<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Student;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        Student::create([
            'student_name' => 'Vedanti Chavan',
            'class_teacher_xid' => 1,
            'class' => '10th Grade',
            'admission_date' => now(),
            'yearly_fees' => 5000.00
        ]);
    }
}
