<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['super_admin', 'school_admin'])
                  ->default('school_admin')
                  ->after('email')
                  ->comment('Role pengguna');
            $table->unsignedBigInteger('school_id')
                  ->nullable()
                  ->after('role')
                  ->comment('ID sekolah yang dimiliki pengguna');
            $table->foreign('school_id')->references('id')->on('schools')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['school_id']);
            $table->dropColumn(['role', 'school_id']);
        });
    }
};
