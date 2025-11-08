<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MedicalRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'pasien_id',
        'praktik_id',
        'tanggal_periksa',
        'diagnosis',
        'obat',
    ];

    public function pasien(): BelongsTo
    {
        return $this->belongsTo(Pasien::class);
    }

    public function praktik(): BelongsTo
    {
        return $this->belongsTo(PendaftaranPraktik::class, 'praktik_id');
    }
}
