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
        Schema::create('penukaran_poin', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
    $table->foreignId('kategori_reward_id')->constrained('kategori_reward')->onDelete('cascade');
    $table->integer('jumlah')->default(1);
    $table->integer('total_poin');
    $table->enum('status', ['pending', 'disetujui', 'ditolak'])->default('pending');
    $table->date('tanggal_penukaran');
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penukaran_poin');
    }
};
