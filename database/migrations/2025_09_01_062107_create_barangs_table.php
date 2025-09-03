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
            $table->id();

            // Kolom foreign key untuk relasi ke tabel kode_barangs
            $table->foreignId('kode_barang_id')->nullable()->constrained('kode_barangs');

            $table->string('nama_barang');
            $table->string('satuan');

            $table->unsignedBigInteger('harga_jual');
            $table->integer('stok')->default(0);
            $table->timestamps();
            $table->softDeletes(); // Langsung tambahkan soft delete di sini
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
