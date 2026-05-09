<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('books', function (Blueprint $table) {
            $table->dropUnique('books_code_unique');
            $table->unique(['school_id', 'code'], 'books_school_id_code_unique');
        });

        Schema::table('students', function (Blueprint $table) {
            $table->dropUnique('students_nis_unique');
            $table->unique(['school_id', 'nis'], 'students_school_id_nis_unique');
        });

        Schema::table('loans', function (Blueprint $table) {
            $table->dropUnique('loans_code_unique');
            $table->unique(['school_id', 'code'], 'loans_school_id_code_unique');
        });
    }

    public function down(): void
    {
        Schema::table('books', function (Blueprint $table) {
            $table->dropUnique('books_school_id_code_unique');
            $table->unique('code', 'books_code_unique');
        });

        Schema::table('students', function (Blueprint $table) {
            $table->dropUnique('students_school_id_nis_unique');
            $table->unique('nis', 'students_nis_unique');
        });

        Schema::table('loans', function (Blueprint $table) {
            $table->dropUnique('loans_school_id_code_unique');
            $table->unique('code', 'loans_code_unique');
        });
    }
};
