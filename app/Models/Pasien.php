<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany; // Import untuk relasi

class Pasien extends Model
{
    use HasFactory;

    // Nama tabel di database
    protected $table = 'pasien';

    /**
     * Kolom yang diizinkan untuk diisi secara massal (Mass Assignment).
     */
    protected $fillable = [
        'nama',
        'tanggal',
        'status',
    ];

    /**
     * Atribut yang harus di-cast ke tipe data asli.
     */
    protected $casts = [
        'tanggal' => 'date', // Mengubah string tanggal dari DB menjadi objek Carbon
    ];
    public function medicalRecords(): HasMany
    {
        // Satu Pasien punya banyak Riwayat Medis
        return $this->hasMany(MedicalRecord::class);
    }
}
