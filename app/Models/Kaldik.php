<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kaldik extends Model
{
    use HasFactory;
    protected $fillable = [
        'tahun',
        'semester',
        'kegiatan',
        'waktu_mulai',
        'waktu_selesai',
        'status',
        'lampiran',
        'keterangan',
    ];
}
