<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Dosen;
use App\Models\Mahasiswa;

class Sp extends Model
{
    use HasFactory;

    protected $table = 'sp';

    protected $primaryKey = 'id_sp';

    public $incrementing = false;

    protected $keyType = 'int';

    protected $fillable = [
        'id_mahasiswa',
        'id_dosen_PA',
        'jenis_sp',
        'lamp_sp',
        'lamp_surat_pernyataan',
        'lamp_pgl_ortu',
        'lamp_ba_ortu',
        'status',
    ];

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'id_mahasiswa', 'id_mahasiswa');
    }

    public function dosen()
    {
        return $this->belongsTo(Dosen::class, 'id_dosen_PA', 'id_dosen');
    }
}