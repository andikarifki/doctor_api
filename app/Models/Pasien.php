<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pasien extends Model
{
    use HasFactory;

    protected $table = 'pasien';

    protected $fillable = [
        'nik',
        'nama',
        'tanggal',
        'status',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    /**
     * Relasi ke Praktik (Many-to-Many via pivot)
     */
    public function praktiks(): BelongsToMany
    {
        return $this->belongsToMany(
            PendaftaranPraktik::class, // Model praktik
            'pasien_praktik',          // Tabel pivot
            'pasien_id',               // FK pivot → pasien
            'praktik_id'               // FK pivot → praktik
        )
            ->withPivot(['tanggal_daftar'])
            ->withTimestamps();
    }

    /**
     * Relasi ke rekam medis (One-to-Many)
     */
    public function medicalRecords(): HasMany
    {
        return $this->hasMany(MedicalRecord::class, 'pasien_id');
    }
}
