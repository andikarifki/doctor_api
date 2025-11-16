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
        Schema::create('request_obat_internal', function (Blueprint $table) {
            $table->id();
            $table->foreignId('obat_id')->constrained('stok_obat')->onDelete('cascade'); // FK ke tabel stok_obat
            $table->integer('jumlah');
            $table->date('tanggal')->default(now());
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('request_obat_internal');
    }
};
