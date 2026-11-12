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
        Schema::create('programme_settings', function (Blueprint $table) {
            $table->id();
            $table->date('start_date')->default('2026-11-02');
            $table->unsignedInteger('weeks')->default(20);
            $table->boolean('include_saturday')->default(false);
            $table->timestamps();
        });

        Schema::create('training_centres', function (Blueprint $table) {
            $table->id();
            $table->string('code', 10);
            $table->string('name');
            $table->string('manager_name')->nullable();
            $table->string('contact')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('topics', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('topic_number')->unique();
            $table->string('title');
            $table->decimal('required_hours', 5, 2);
            $table->timestamps();
        });

        Schema::create('lecturers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('topic_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('centre_id')->constrained('training_centres')->cascadeOnDelete();
            $table->foreignId('topic_id')->constrained('topics')->cascadeOnDelete();
            $table->foreignId('lecturer_id')->constrained('lecturers')->cascadeOnDelete();
            $table->unique(['centre_id', 'topic_id', 'lecturer_id']);
            $table->timestamps();
        });

        Schema::create('timetable_slots', function (Blueprint $table) {
            $table->id();
            $table->foreignId('centre_id')->constrained('training_centres')->cascadeOnDelete();
            $table->unsignedInteger('week');
            $table->unsignedInteger('day');
            $table->unsignedInteger('slot_index');
            $table->foreignId('topic_id')->nullable()->constrained('topics')->nullOnDelete();
            $table->boolean('is_other_topic')->default(false);
            $table->foreignId('lecturer_id')->nullable()->constrained('lecturers')->nullOnDelete();
            $table->string('unit_note')->nullable();
            $table->boolean('is_held')->default(false);
            $table->unique(['centre_id', 'week', 'day', 'slot_index']);
            $table->timestamps();
        });

        Schema::create('days_off', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('week');
            $table->unsignedInteger('day');
            $table->boolean('is_off')->default(true);
            $table->unique(['week', 'day']);
            $table->timestamps();
        });

        Schema::create('topic_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('centre_id')->constrained('training_centres')->cascadeOnDelete();
            $table->foreignId('topic_id')->constrained('topics')->cascadeOnDelete();
            $table->decimal('manual_hours', 6, 2)->default(0);
            $table->string('remarks')->nullable();
            $table->unique(['centre_id', 'topic_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('topic_progress');
        Schema::dropIfExists('days_off');
        Schema::dropIfExists('timetable_slots');
        Schema::dropIfExists('topic_assignments');
        Schema::dropIfExists('lecturers');
        Schema::dropIfExists('topics');
        Schema::dropIfExists('training_centres');
        Schema::dropIfExists('programme_settings');
    }
};
