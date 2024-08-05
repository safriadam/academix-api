<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Kompensasi;
use App\Models\Logs;
use App\Models\Mahasiswa;

class Cicil_kompen extends Model
{
    use HasFactory;

    protected $table = 'cicil_kompen';

    protected $primaryKey = 'id_cicil';

    protected $KeyType = 'int';

    public $Increment = false;

    public $timestamps = true;

    protected $fillable = [
        'id_kompen',
        'id_tahun_ajar',
        'id_mahasiswa',
        'tgl_cicil',
        'jlh_jam_konversi',
        'jenis_kompen',
        'status',
    ];

    public function kompen_mahasiswa()
    {
        return $this->belongsTo(Kompen_mahasiswa::class, 'id_kompen' ,'id_kompen');
    }

    public function logs()
    {
        return $this->belongsTo(Logs::class, 'id_tahun_ajar' ,'id_tahun_ajar');
    }

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'id_mahasiswa' ,'id_mahasiswa');
    }
}