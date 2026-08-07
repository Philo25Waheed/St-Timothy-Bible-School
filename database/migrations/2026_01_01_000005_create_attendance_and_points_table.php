<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attendance_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('class_id')->constrained('classes')->onDelete('cascade');
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->foreignId('recorded_by')->nullable()->constrained('users')->onDelete('set null');
            $table->date('date');
            $table->enum('status', ['present', 'absent', 'late', 'excused'])->default('present');
            $table->string('notes')->nullable();
            $table->timestamps();

            $table->unique(['student_id', 'date']);
        });

        Schema::create('student_points', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->foreignId('given_by')->nullable()->constrained('users')->onDelete('set null');
            $table->integer('amount'); // +10 or -5
            $table->string('reason');
            $table->string('category')->default('general'); // attendance, quiz, verse, behavior
            $table->timestamps();
        });

        Schema::create('achievements', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('icon')->nullable();
            $table->string('badge_code')->unique(); // e.g. bible_scholar, perfect_attendance, memory_master
            $table->timestamps();
        });

        Schema::create('student_achievements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->foreignId('achievement_id')->constrained('achievements')->onDelete('cascade');
            $table->timestamp('awarded_at')->nullable();
            $table->timestamps();

            $table->unique(['student_id', 'achievement_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_achievements');
        Schema::dropIfExists('achievements');
        Schema::dropIfExists('student_points');
        Schema::dropIfExists('attendance_records');
    }
};
