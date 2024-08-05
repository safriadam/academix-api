<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Cicil_kompen;
use App\Models\Matkul;
use App\Models\Logs;
use App\Models\Mahasiswa;

class Kompen_mahasiswa extends Model
{
    use HasFactory;

    // nonaktifkan timestamp karena di tabel tidak memakai kolom 'create_at' & 'update_at
    public $timestamps = false;

    // menghubungkan nama tabel dengan model
    protected $table = 'kompen_mhs';

    // menentukan primary key pada kolom tabel
    protected $primaryKey = 'id_kompen';

    // menentukan apakah primary key auto increment pada tabel
    public $incrementting = true;

    // menentukan tipe data primary key
    protected $keyType = 'int';

    // menentukan kolom kolom lain pada tabel (yang bukan primary key)
    protected $fillable = [
        'id_matkul',
        'id_tahun_ajar',
        'id_mahasiswa',
        'jumlah_kompen',
        'keterangan',
        'tgl_alpha',
    ];

    public function cicil_kompen()
    {
        return $this->belongsTo(Cicil_kompen::class,'id_kompen','id_kompen');
    }

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class,'id_mahasiswa','id_mahasiswa');
    }

    public function matkul()
    {
        return $this->hasOne(Matkul::class,'id_matkul','id_matkul');
    }

    public function logs()
    {
        return $this->belongsTo(Logs::class,'id_tahun_ajar','id_tahun_ajar');
    }

}