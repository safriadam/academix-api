<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Presensi;
use App\Models\Jadwal;
use App\Models\Dosen;
use App\Models\Kompen_mahasiswa;

class Logs extends Model
{
    use HasFactory;

    // nonaktifkan timestamp karna di tabel tidak memakai kolom 'created_at' & 'updated_at'
    public $timestamps = false;

    // menghubungkan nama tabel dengan model
    protected $table = 'logs';

    // menentukan apakah primary key auto increment pada tabel
    public $incrementing = true;

    // menentukan primary key pada kolom tabel
    protected $primaryKey = 'id_tahun_ajar';

    // menentukan tipe data primary key
    protected $keyType = 'int';

    // menentukan kolom kolom lain pada tabel (yang bukan primary key)
    protected $fillable = [
            'id_jadwal',
            'id_dosen',
            'tahun_awal',
            'tahun_akhir',
        ];

        // menentukan relasi tabel

        public function presensi ()
        {
            return $this->hasOne(Presensi::class, 'id_tahun_ajar', 'id_tahun_ajar');
        }

        public function kompen_mahasiswa()
        {
            return $this->hasOne(Kompen_mahasiswa::class, 'id_tahun_ajar', 'id_tahun_ajar');
        }

        public function jadwal ()
        {
            return $this ->belongsTo(Jadwal::class, 'id_jadwal', 'id_jadwal');
        }

        public function dosen ()
        {
            return  $this -> belongsTo(Dosen::class, 'id_dosen', 'id_dosen');
        }
}