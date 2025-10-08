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
        Schema::table('pasien', function (Blueprint $table) {
            Schema::table('pasien', function (Blueprint $table) {
                // Mengubah kolom 'status' dari ENUM menjadi VARCHAR (string)
                // dengan panjang maksimal 20 karakter dan default 'Aktif'.
                $table->string('status', 20)
                    ->default('Aktif')
                    ->change();
            });
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pasien', function (Blueprint $table) {
            //
        });
    }
};
