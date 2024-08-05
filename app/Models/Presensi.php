<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Jadwal;
use App\Models\Logs;
use App\Models\Mahasiswa;
use App\Models\revisi_presensi;
use App\Models\keterangan_mahasiswa;

class Presensi extends Model
{
    use HasFactory;

    protected $table = 'presensi';

    protected $primaryKey = 'id_presensi';

    public $incrementing = true;

    protected $keyType = 'int';

    protected $fillable = [
        'id_mahasiswa',
        'id_tahun_ajar',
        'id_jadwal',
        'tanggal',
        'start_kls',
        'finish_kls',
        'kehadiran',
        'ketidakhadiran',
        'status',
    ];

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'id_mahasiswa', 'id_mahasiswa');
    }

    public function revisi_presensi()
    {
        return $this->hasOne(Revisi_presensi::class, 'id_presensi', 'id_presensi');
    }

    public function keterangan_mahasiswa()
    {
        return $this->hasOne(Keterangan_mahasiswa::class, 'id_presensi', 'id_presensi');
    }

    public function logs()
    {
        return $this->belongsTo(Logs::class, 'id_tahun_ajar' ,'id_tahun_ajar');
    }

    public function jadwal()
    {
        return $this->belongsTo(Jadwal::class,'id_jadwal' ,'id_jadwal');
    }
}