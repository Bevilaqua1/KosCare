<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
{
    Schema::table('kategori_sampah', function (Blueprint $table) {
        $table->integer('poin_per_kg')->default(0)->after('nama_kategori');
    });
}

public function down()
{
    Schema::table('kategori_sampah', function (Blueprint $table) {
        $table->dropColumn('poin_per_kg');
    });
}
};
