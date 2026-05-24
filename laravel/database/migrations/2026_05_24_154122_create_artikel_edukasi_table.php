<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('artikel_edukasi', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->text('isi');
            $table->string('gambar')->nullable();
            $table->date('tanggal_terbit');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('artikel_edukasi');
    }
};