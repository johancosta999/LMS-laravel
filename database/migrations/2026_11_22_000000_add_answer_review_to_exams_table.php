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
        // when students may see the quiz questions they got wrong, with the correct answers
        Schema::table('exams', function (Blueprint $table) {
            $table->string('review_mode')->default('never')->after('paper_path'); // never | immediate | date
            $table->timestamp('review_available_at')->nullable()->after('review_mode');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('exams', function (Blueprint $table) {
            $table->dropColumn(['review_mode', 'review_available_at']);
        });
    }
};
