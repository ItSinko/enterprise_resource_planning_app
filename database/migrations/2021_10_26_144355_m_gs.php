<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MGs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('m_gs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('sparepart_id');
            $table->foreign('sparepart_id')->references('id')->on('m_sparepart');
            $table->string('nama')->nullable();
            $table->string('deskripsi')->nullable();
            $table->integer('stok')->nullable();
            $table->unsignedBigInteger('layout_id')->nullable();
            $table->foreign('layout_id')->references('id')->on('m_layout');
            $table->string('gambar')->nullable();
            $table->string('dim_p')->nullable();
            $table->string('dim_l')->nullable();
            $table->string('dim_t')->nullable();
            $table->string('status')->nullable()->comment('status sparepart');
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
        Schema::dropIfExists('m_gs');
    }
}
