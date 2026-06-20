<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detail_setoran', function (Blueprint $table) {
            $table->id();
            $table->foreignId('setoran_id')
                  ->constrained('setoran')
                  ->onDelete('cascade');
            $table->foreignId('toko_id')
                  ->constrained('toko')
                  ->onDelete('cascade');
            $table->integer('jumlah_terjual');
            $table->decimal('harga_satuan', 10, 2);
            $table->decimal('total_uang', 12, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detail_setoran');
    }
};