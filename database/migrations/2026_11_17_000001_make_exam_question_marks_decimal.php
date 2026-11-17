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
        // default mark = full marks / number of questions, e.g. 100 / 3 = 33.33
        Schema::table('exam_questions', function (Blueprint $table) {
            $table->decimal('marks', 6, 2)->default(1)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('exam_questions', function (Blueprint $table) {
            $table->unsignedInteger('marks')->default(1)->change();
        });
    }
};
