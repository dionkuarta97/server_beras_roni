<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('campurans', function (Blueprint $table) {
            $table->id();
            $table->integer('idBerasKelola');
            $table->integer('idBerasCampur');
            $table->string('kategori');
            $table->integer('harga');
            $table->integer('berat');
            $table->string('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('campurans');
    }
};
