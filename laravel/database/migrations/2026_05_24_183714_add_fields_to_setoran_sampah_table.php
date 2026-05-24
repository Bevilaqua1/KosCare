<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('setoran_sampah', function (Blueprint $table) {
        // Hapus kolom status lama jika sudah ada enum yang berbeda, atau langsung tambahkan
        if (!Schema::hasColumn('setoran_sampah', 'foto')) {
            $table->string('foto')->nullable()->after('berat_estimasi');
        }
        if (!Schema::hasColumn('setoran_sampah', 'status')) {
            // Pastikan enum sesuai: pending, diangkut, selesai
            $table->enum('status', ['pending', 'diangkut', 'selesai'])->default('pending')->after('foto');
        }
        if (!Schema::hasColumn('setoran_sampah', 'berat_aktual')) {
            $table->decimal('berat_aktual', 8, 2)->nullable()->after('status');
        }
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
        $table->dropColumn(['foto', 'status', 'berat_aktual', 'poin_didapat', 'petugas_id']);
    });
}
};
