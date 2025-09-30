<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo; // Import untuk relasi

class MedicalRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'pasien_id',
        'tanggal_periksa',
        'diagnosis',
        'obat',
        'lokasi_berobat',
    ];

    /**
     * Relasi ke Pasien (Patient).
     */
    public function pasien(): BelongsTo
    {
        // Satu Riwayat Medis hanya dimiliki oleh satu Pasien
        return $this->belongsTo(Pasien::class);
    }
}
