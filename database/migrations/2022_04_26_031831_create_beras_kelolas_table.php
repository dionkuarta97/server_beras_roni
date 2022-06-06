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
        Schema::create('beras_kelolas', function (Blueprint $table) {
            $table->id();
            $table->integer('idModal');
            $table->string('keterangan');
            $table->integer('harga');
            $table->integer('berat');
            $table->integer('stock');
            $table->string('tipe')->nullable();
            $table->string('nama_pembuat');
            $table->string('status')->default('active');
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
        Schema::dropIfExists('beras_kelolas');
    }
};
