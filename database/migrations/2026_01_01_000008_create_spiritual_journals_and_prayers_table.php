<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('spiritual_journals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->string('title');
            $table->text('content');
            $table->string('mood')->nullable();
            $table->timestamps();
        });

        Schema::create('prayer_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->foreignId('servant_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('title');
            $table->text('details');
            $table->enum('status', ['pending', 'praying', 'answered'])->default('pending');
            $table->text('servant_notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('prayer_requests');
        Schema::dropIfExists('spiritual_journals');
    }
};
