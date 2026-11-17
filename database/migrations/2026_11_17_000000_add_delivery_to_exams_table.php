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
        // how the exam is delivered: a pdf paper or an online quiz
        Schema::table('exams', function (Blueprint $table) {
            $table->string('exam_type')->default('paper')->after('description'); // paper | quiz
            $table->string('enrol_key')->nullable()->after('exam_type');
            $table->unsignedInteger('duration_minutes')->default(60)->after('enrol_key');
            $table->string('paper_path')->nullable()->after('duration_minutes');
        });

        // multiple choice questions of an online quiz
        Schema::create('exam_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exam_id')->constrained('exams')->cascadeOnDelete();
            $table->text('question');
            $table->json('options');
            $table->unsignedTinyInteger('correct_option'); // index into options
            $table->unsignedInteger('marks')->default(1);
            $table->timestamps();
        });

        // one student's sitting of an exam (started with the key, timed)
        Schema::create('exam_attempts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exam_id')->constrained('exams')->cascadeOnDelete();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->timestamp('started_at');
            $table->timestamp('submitted_at')->nullable();
            $table->json('answers')->nullable(); // question_id => chosen option index
            $table->decimal('score', 6, 2)->nullable();
            $table->timestamps();

            $table->unique(['exam_id', 'student_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exam_attempts');
        Schema::dropIfExists('exam_questions');

        Schema::table('exams', function (Blueprint $table) {
            $table->dropColumn(['exam_type', 'enrol_key', 'duration_minutes', 'paper_path']);
        });
    }
};
