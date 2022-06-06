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
        Schema::create('modal_kelolas', function (Blueprint $table) {
            $table->id();
            $table->integer('idBerasKelola');
            $table->string('keterangan');
            $table->integer('harga');
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
        Schema::dropIfExists('modal_kelolas');
    }
};
