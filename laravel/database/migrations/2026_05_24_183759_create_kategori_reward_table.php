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
        Schema::create('kategori_reward', function (Blueprint $table) {
    $table->id();
    $table->string('nama_item');
    $table->text('deskripsi')->nullable();
    $table->integer('poin_diperlukan');
    $table->integer('stok')->default(0);
    $table->string('gambar')->nullable();
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kategori_reward');
    }
};
