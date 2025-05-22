<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('user', function (Blueprint $table) {
            $table->id('id_user');
            $table->string('nama_user');
            $table->string('no_telepon');
            $table->string('email')->unique();
            $table->string('password');
            $table->enum('role', ['admin', 'user'])->default('user');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('user');
    }
};
