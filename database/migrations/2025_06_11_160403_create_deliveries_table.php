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
        Schema::create('deliveries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // relasi ke sales
            $table->foreignId('store_id')->constrained()->onDelete('cascade'); // relasi ke toko
            $table->integer('quantity'); // jumlah barang
            $table->integer('total_price'); // harga total (otomatis dari jumlah x harga satuan)
            $table->date('delivery_date'); // tanggal otomatis
            $table->boolean('is_submitted')->default(false); // laporan sudah dikirim atau belum
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deliveries');
    }
};
