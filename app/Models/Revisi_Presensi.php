<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Presensi;

class Revisi_presensi extends Model
{
    use HasFactory;

    protected $table = 'revisi_presensi';

    protected $primaryKey = 'id_revisi_presensi';

    public $incrementing = true;

    protected $keyType = 'int';

    protected $fillable = [
        'id_presensi',
        'tanggal_revisi',
        'status',
        'bukti_revisi',
        'created_at',
        'revisi',
    ];
    // menentukan relasi tabel
    public function presensi ()
    {
        return $this->belongsTo(Presensi::class, 'id_presensi', 'id_presensi');
    }
}