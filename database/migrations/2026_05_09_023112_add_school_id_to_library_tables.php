<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('books', function (Blueprint $table) {
            $table->unsignedBigInteger('school_id')
                  ->nullable()
                  ->after('id')
                  ->comment('ID sekolah pemilik buku');
            $table->foreign('school_id')->references('id')->on('schools')->onDelete('cascade');
        });

        Schema::table('students', function (Blueprint $table) {
            $table->unsignedBigInteger('school_id')
                  ->nullable()
                  ->after('id')
                  ->comment('ID sekolah pemilik data siswa');
            $table->foreign('school_id')->references('id')->on('schools')->onDelete('cascade');
        });

        Schema::table('loans', function (Blueprint $table) {
            $table->unsignedBigInteger('school_id')
                  ->nullable()
                  ->after('id')
                  ->comment('ID sekolah pemilik data peminjaman');
            $table->foreign('school_id')->references('id')->on('schools')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('books', function (Blueprint $table) {
            $table->dropForeign(['school_id']);
            $table->dropColumn('school_id');
        });

        Schema::table('students', function (Blueprint $table) {
            $table->dropForeign(['school_id']);
            $table->dropColumn('school_id');
        });

        Schema::table('loans', function (Blueprint $table) {
            $table->dropForeign(['school_id']);
            $table->dropColumn('school_id');
        });
    }
};
