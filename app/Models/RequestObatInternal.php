<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequestObatInternal extends Model
{
    use HasFactory;

    protected $table = 'request_obat_internal'; // nama tabel sesuai migration

    protected $fillable = [
        'obat_id',
        'jumlah',
        'tanggal',
        'status',
        'keterangan',
    ];

    // Relasi ke StokObat
    public function obat()
    {
        return $this->belongsTo(StokObat::class, 'obat_id');
    }
}
