<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('setoran_sampah', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('kategori_id')->constrained('kategori_sampah')->onDelete('cascade');
            $table->decimal('estimasi_berat', 8, 2); // dalam kg
            $table->decimal('berat_aktual', 8, 2)->nullable();
            $table->date('tanggal_setor');
            $table->string('foto')->nullable(); // path foto
            $table->enum('status', ['pending', 'disetujui', 'selesai'])->default('pending');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('setoran_sampah');
    }
};