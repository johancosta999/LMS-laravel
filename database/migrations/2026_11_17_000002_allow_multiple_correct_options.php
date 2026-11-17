<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // a question can have more than one correct answer: store a list of option indexes
        Schema::table('exam_questions', function (Blueprint $table) {
            $table->json('correct_options')->nullable()->after('options');
        });

        DB::table('exam_questions')->orderBy('id')->each(function ($q) {
            DB::table('exam_questions')->where('id', $q->id)
                ->update(['correct_options' => json_encode([(int) $q->correct_option])]);
        });

        Schema::table('exam_questions', function (Blueprint $table) {
            $table->dropColumn('correct_option');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('exam_questions', function (Blueprint $table) {
            $table->unsignedTinyInteger('correct_option')->default(0)->after('options');
        });

        // keeps only the first correct answer
        DB::table('exam_questions')->orderBy('id')->each(function ($q) {
            DB::table('exam_questions')->where('id', $q->id)
                ->update(['correct_option' => (int) (json_decode($q->correct_options, true)[0] ?? 0)]);
        });

        Schema::table('exam_questions', function (Blueprint $table) {
            $table->dropColumn('correct_options');
        });
    }
};
