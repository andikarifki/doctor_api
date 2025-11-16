<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PendaftaranPraktik extends Model
{
    use HasFactory;

    protected $table = 'pendaftaran_praktik';

    protected $fillable = [
        'lokasi_praktik',
    ];

    /**
     * Relasi ke pasien — satu praktik bisa memiliki banyak pasien.
     */
    public function pasiens()
    {
        return $this->belongsToMany(
            Pasien::class,
            'pasien_praktik',
            'praktik_id',
            'pasien_id'
        )
            ->withPivot(['tanggal_daftar'])
            ->withTimestamps();
    }

    /**
     * Relasi ke rekam medis — satu praktik bisa memiliki banyak medical record.
     */
    public function medicalRecords(): HasMany
    {
        return $this->hasMany(MedicalRecord::class, 'praktik_id');
    }

    /**
     * Casting otomatis untuk tanggal
     */
    protected $casts = [
        'tanggal_daftar' => 'date',
    ];
}
