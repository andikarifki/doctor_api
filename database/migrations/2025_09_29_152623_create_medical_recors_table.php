<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('medical_recors', function (Blueprint $table) {
            $table->id();
            // 🟢 FIX: Gunakan foreignId() dan pastikan merujuk ke 'patients'
            $table->foreignId('pasien_id')
                ->constrained('pasien')
                ->onDelete('cascade');

            $table->date('tanggal_periksa');
            $table->text('diagnosis');
            $table->text('obat');
            $table->string('lokasi_berobat')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medical_recors');
    }
};
