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
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('student_name');
            $table->unsignedBigInteger('class_teacher_xid')->nullable();
            $table->string('class');
            $table->date('admission_date');
            $table->integer('yearly_fees');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('class_teacher_xid')->references('id')->on('teachers')->cascadeOnDelete();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
