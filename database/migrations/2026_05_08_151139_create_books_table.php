<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique()->comment('Kode buku');
            $table->string('title')->comment('Judul buku');
            $table->string('category')->comment('Kategori buku');
            $table->string('author')->comment('Penulis');
            $table->string('publisher')->comment('Penerbit');
            $table->year('publish_year')->comment('Tahun terbit');
            $table->string('shelf')->comment('Rak/Lokasi buku');
            $table->integer('stock')->default(1)->comment('Stok buku');
            $table->string('cover')->nullable()->comment('Cover buku');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
