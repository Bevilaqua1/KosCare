<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('no_kamar')->nullable();
            $table->string('no_wa', 15)->nullable();
            $table->enum('role', ['resident', 'admin'])->default('resident');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['no_kamar', 'no_wa', 'role']);
        });
    }
};