<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('pengaduans', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            $table->string('nama_pelapor');
            $table->string('jenis_pengaduan');
            $table->text('deskripsi');
            $table->enum('status', ['Menunggu', 'Proses', 'Selesai'])->default('Menunggu');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pengaduans');
    }
};
