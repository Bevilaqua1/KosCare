<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('setoran_sampah', function (Blueprint $table) {
            $table->foreignId('jadwal_id')
                  ->nullable()
                  ->after('petugas_id')
                  ->constrained('jadwal_pengangkutan')
                  ->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('setoran_sampah', function (Blueprint $table) {
            $table->dropForeign(['jadwal_id']);
            $table->dropColumn('jadwal_id');
        });
    }
};