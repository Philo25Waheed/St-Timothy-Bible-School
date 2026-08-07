<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('academic_years', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g. 2025/2026
            $table->boolean('is_current')->default(true);
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->timestamps();
        });

        Schema::create('stages', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g. المرحلة الابتدائية, المرحلة الإعدادية, المرحلة الثانوية
            $table->text('description')->nullable();
            $table->integer('order')->default(1);
            $table->timestamps();
        });

        Schema::create('grades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('stage_id')->constrained('stages')->onDelete('cascade');
            $table->string('name'); // e.g. الصف الأول, الصف الثاني
            $table->integer('order')->default(1);
            $table->timestamps();
        });

        Schema::create('classes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('grade_id')->constrained('grades')->onDelete('cascade');
            $table->string('name'); // e.g. فصل القديس مارمرقس
            $table->string('room')->nullable();
            $table->foreignId('servant_id')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('classes');
        Schema::dropIfExists('grades');
        Schema::dropIfExists('stages');
        Schema::dropIfExists('academic_years');
    }
};
