<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// hubungkan model model yang diperlukan
use app\Models\User;
use app\Models\Dosen;
use App\Models\Kompen_mahasiswa;
use App\Models\Kelas;
use App\Models\Presensi;

class Mahasiswa extends Model
{
    use HasFactory;

        // nonaktifkan timestamp karna di tabel tidak memakai kolom 'created_at' & 'updated_at'
        public $timestamps = false;

        // menghubungkan nama tabel dengan model
        protected $table = 'mahasiswa';
    
        // menentukan primary key pada kolom tabel
        protected $primaryKey = 'id_mahasiswa';
    
        // menentukan apakah primary key auto increment pada tabel
        public $incrementing = true;
    
        // menentukan tipe data primary key
        protected $keyType = 'int';
    
        // menentukan kolom kolom lain pada tabel (yang bukan primary key)
        protected $fillable = [
            'user_id',
            'id_kelas',
            'id_dosen_PA',
            'nim',
            'nama',
            'nama_ortu',
            'no_hp_ortu',
            'foto',
            'no_hp',
            'ket_status',
        ];
    
        // menentukan relasi tabel 
        public function user()
        {
            return $this->belongsTo(User::class, 'user_id', 'id');
        }

        public function dosen()
        {
            return $this->belongsTo(Dosen::class, 'id_dosen_PA', 'id_dosen');
        }

        public function kompen_mahasiswa()
        {
            return $this->hasOne(Kompen_mahasiswa::class,'id_presensi','id_presensi');
        }

        public function kelas()
        {
            return $this->hasMany(Kelas::class,'id_kelas','id_kelas');
        }

        public function presensi()
        {
            return $this->hansOne(Presensi::class,'id_mahasiswa','id_mahasiswa');
        }
}