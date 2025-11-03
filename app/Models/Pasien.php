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
        'praktik_id',
        'nik',
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

    public function praktiks()
    {
        return $this->belongsToMany(
            PendaftaranPraktik::class,  // model praktik kamu (kalau namanya lain sesuaikan)
            'pasien_praktik',           // nama tabel pivot
            'pasien_id',                // foreign key di pivot
            'praktik_id'                // foreign key di tabel tujuan
        )->withPivot(['tanggal_daftar', 'status'])
            ->withTimestamps();
    }

    public function medicalRecords(): HasMany
    {
        // Satu Pasien punya banyak Riwayat Medis
        return $this->hasMany(MedicalRecord::class);
    }
}
