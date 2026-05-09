<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('schools', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 255)->comment('Nama sekolah');
            $table->string('slug', 50)->unique()->comment('Identifier unik sekolah');
            $table->string('admin_name', 255)->comment('Nama penanggung jawab');
            $table->enum('subscription_status', ['trial', 'active', 'expired', 'suspended'])
                  ->default('trial')
                  ->comment('Status langganan');
            $table->timestamp('trial_ends_at')->nullable()->comment('Tanggal berakhir masa trial');
            $table->timestamp('subscription_ends_at')->nullable()->comment('Tanggal berakhir langganan');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('schools');
    }
};
