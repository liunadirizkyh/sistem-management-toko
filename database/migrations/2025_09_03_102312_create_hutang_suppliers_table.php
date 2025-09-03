<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('hutang_suppliers', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal_datang');
            $table->string('nama_supplier');
            $table->string('kode_nota')->unique();
            $table->text('nama_barang');
            $table->unsignedBigInteger('harga_total');
            $table->date('tanggal_jatuh_tempo');
            $table->unsignedBigInteger('jumlah_dibayar')->default(0);
            $table->date('tanggal_bayar')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hutang_suppliers');
    }
};
