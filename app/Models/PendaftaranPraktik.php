<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PendaftaranPraktik extends Model
{
    use HasFactory;

    /**
     * Nama tabel di database
     */
    protected $table = 'pendaftaran_praktik';

    /**
     * Kolom yang boleh diisi secara massal.
     */
    protected $fillable = [
        'lokasi_praktik',
        'tanggal_daftar',
    ];

    /**
     * Relasi ke pasien — satu praktik bisa memiliki banyak pasien.
     */
    public function pasiens()
    {
        return $this->belongsToMany(
            Pasien::class,          // Model tujuan
            'pasien_praktik',       // Tabel pivot
            'praktik_id',           // Foreign key di tabel pivot yang mengarah ke tabel ini
            'pasien_id'             // Foreign key di tabel pivot yang mengarah ke tabel pasien
        )
            ->withPivot(['tanggal_daftar'])
            ->withTimestamps();
    }

    /**
     * Casting otomatis — ubah string tanggal menjadi objek Carbon.
     */
    protected $casts = [
        'tanggal_daftar' => 'date',
    ];
}
