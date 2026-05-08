<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('nis')->unique()->comment('Nomor Induk Siswa');
            $table->string('name')->comment('Nama siswa');
            $table->string('class')->comment('Kelas');
            $table->string('major')->comment('Jurusan');
            $table->string('phone')->nullable()->comment('Nomor HP');
            $table->text('address')->nullable()->comment('Alamat');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
