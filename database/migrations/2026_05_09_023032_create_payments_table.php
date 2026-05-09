<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('school_id')->constrained('schools')->onDelete('cascade');
            $table->bigInteger('amount')->comment('Nominal pembayaran dalam Rupiah');
            $table->date('transfer_date')->comment('Tanggal transfer');
            $table->string('bank_name', 100)->comment('Nama bank pengirim');
            $table->text('notes')->nullable()->comment('Catatan tambahan');
            $table->enum('status', ['pending', 'approved', 'rejected'])
                  ->default('pending')
                  ->comment('Status verifikasi pembayaran');
            $table->text('rejection_reason')->nullable()->comment('Alasan penolakan');
            $table->timestamp('reviewed_at')->nullable()->comment('Waktu review');
            $table->unsignedBigInteger('reviewed_by')->nullable()->comment('ID admin yang mereview');
            $table->foreign('reviewed_by')->references('id')->on('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
