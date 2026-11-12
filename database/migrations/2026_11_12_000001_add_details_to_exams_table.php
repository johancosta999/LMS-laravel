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
        Schema::table('exams', function (Blueprint $table) {
            $table->foreignId('teacher_id')->after('id')->constrained('teachers')->cascadeOnDelete();
            $table->foreignId('subject_id')->after('teacher_id')->constrained('subjects')->cascadeOnDelete();
            $table->string('title')->after('subject_id');
            $table->date('exam_date')->after('title');
            $table->integer('total_marks')->after('exam_date');
            $table->integer('pass_marks')->after('total_marks');
            $table->text('description')->nullable()->after('pass_marks');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('exams', function (Blueprint $table) {
            $table->dropForeign(['teacher_id']);
            $table->dropForeign(['subject_id']);
            $table->dropColumn(['teacher_id', 'subject_id', 'title', 'exam_date', 'total_marks', 'pass_marks', 'description']);
        });
    }
};
