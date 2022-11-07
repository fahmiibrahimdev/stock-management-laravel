<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePembelianBarangsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pembelian_barang', function (Blueprint $table) {
            $table->id();
            $table->text('tanggal_group');
            $table->text('tanggal');
            $table->text('id_user_request');
            $table->text('id_user_accept');
            $table->text('kode_resi');
            $table->text('id_kurir');
            $table->text('nama_barang');
            $table->text('qty');
            $table->text('harga');
            $table->text('total');
            $table->text('keterangan');
            $table->text('link_pembelian');
            $table->text('status');
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
        Schema::dropIfExists('pembelian_barang');
    }
}
