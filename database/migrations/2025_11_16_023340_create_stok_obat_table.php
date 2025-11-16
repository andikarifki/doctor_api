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
        Schema::create('stok_obat', function (Blueprint $table) {
            $table->id();
            $table->string('nama_obat');
            $table->integer('stok')->default(0);
            $table->string('satuan')->nullable();
            $table->string('kategori')->nullable();
            $table->date('expired_date')->nullable();
            $table->decimal('harga', 12, 2)->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stok_obat');
    }
};
