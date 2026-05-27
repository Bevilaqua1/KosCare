<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('setoran_sampah', function (Blueprint $table) {
            if (!Schema::hasColumn('setoran_sampah', 'poin_didapat')) {
                $table->integer('poin_didapat')->default(0)->after('berat_aktual');
            }
            if (!Schema::hasColumn('setoran_sampah', 'petugas_id')) {
                $table->foreignId('petugas_id')->nullable()->constrained('users')->onDelete('set null')->after('user_id');
            }
        });
    }

    public function down()
    {
        Schema::table('setoran_sampah', function (Blueprint $table) {
            $table->dropForeign(['petugas_id']);
            $table->dropColumn(['poin_didapat', 'petugas_id']);
        });
    }
};