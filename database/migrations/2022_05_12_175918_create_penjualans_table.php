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
        Schema::create('penjualans', function (Blueprint $table) {
            $table->id();
            $table->integer('idKategori');
            $table->integer('idModal')->nullable();
            $table->integer('idBerasKalola');
            $table->text('keterangan');
            $table->integer('bobot');
            $table->integer('harga_modal');
            $table->integer('harga_jual');
            $table->string('tipe')->nullable();
            $table->integer('idLangganan')->nullable();
            $table->string('jenis_pembayaran');
            $table->string('nama_pembuat');
            $table->string('nama_pembeli')->nullable();
            $table->string('status')->default('pending');
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
        Schema::dropIfExists('penjualans');
    }
};
