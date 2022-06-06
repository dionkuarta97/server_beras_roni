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
        Schema::create('riwayat_modal_datangs', function (Blueprint $table) {
            $table->id();
            $table->string('keterangan');
            $table->integer('harga');
            $table->integer('idModalDatang');
            $table->string('nama_pembuat');
            $table->string('status');
            $table->string('nama_pengubah');
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
        Schema::dropIfExists('riwayat_modal_datangs');
    }
};
