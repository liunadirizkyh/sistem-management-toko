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
        Schema::create('barangs', function (Blueprint $table) {
            $table->id(); // ID Barang (auto-generated)
            $table->string('kode_barang')->unique()->nullable();
            $table->string('nama_barang');
            $table->string('satuan');
            $table->unsignedBigInteger('harga_beli'); // Modal
            $table->unsignedBigInteger('harga_jual'); // Harga Jual
            $table->integer('stok')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barangs');
    }
};
