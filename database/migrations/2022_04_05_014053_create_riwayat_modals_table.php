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
        Schema::create('riwayat_modals', function (Blueprint $table) {
            $table->id();
            $table->integer('idModal');
            $table->text('keterangan');
            $table->integer('idCategory');
            $table->text('modal');
            $table->integer("idUser");
            $table->string('nama_pembuat');
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
        Schema::dropIfExists('riwayat_modals');
    }
};
