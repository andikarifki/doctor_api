<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StokObat extends Model
{
    use HasFactory;

    protected $table = 'stok_obat';

    protected $fillable = [
        'nama_obat',
        'stok',
        'satuan',
        'kategori',
        'expired_date',
        'harga',
        'keterangan',
    ];
}
