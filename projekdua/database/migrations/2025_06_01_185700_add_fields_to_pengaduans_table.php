<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsToPengaduansTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('pengaduans', function (Blueprint $table) {
            $table->string('waktu')->after('tanggal')->nullable();
            $table->string('kecamatan')->after('jenis_pengaduan')->nullable();
            $table->string('desa')->after('kecamatan')->nullable();
            $table->string('alamat')->after('desa')->nullable();
            $table->string('media_uri')->nullable()->after('alamat');
            $table->string('media_type')->nullable()->after('media_uri');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pengaduans', function (Blueprint $table) {
            $table->dropColumn(['waktu', 'kecamatan', 'desa', 'alamat', 'media_uri', 'media_type']);
        });
    }
}
