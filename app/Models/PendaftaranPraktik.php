<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PendaftaranPraktik extends Model
{
    use HasFactory;

    // Definisikan nama tabel secara eksplisit
    protected $table = 'pendaftaran_praktik';

    /**
     * The attributes that are mass assignable.
     * Kolom-kolom yang aman untuk diisi secara massal (melalui create atau update).
     * Pastikan hanya kolom yang ada di migrasi yang ada di sini.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'lokasi_praktik',
        'tanggal_daftar',
    ];

    /**
     * The attributes that should be cast.
     * Konversi tipe data otomatis (Casting).
     *
     * @var array<string, string>
     */
    protected $casts = [
        // Mengubah string tanggal dari database menjadi objek Carbon (untuk kemudahan manipulasi di PHP)
        'tanggal_daftar' => 'date',
    ];
}
