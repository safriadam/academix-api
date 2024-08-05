<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// hubungkan model model yang diperlukan
use app\Models\jadwal;

class Matkul extends Model
{
    use HasFactory;

    // nonaktifkan timestamp karna di tabel tidak memakai kolom 'created_at' & 'updated_at'
    public $timestamps = false;

    // menghubungkan nama tabel dengan model
    protected $table = 'matkul';

    // menentukan primary key pada kolom tabel
    protected $primaryKey = 'id_matkul';

    // menentukan apakah primary key auto increment pada tabel
    public $incrementing = true;

    // menentukan tipe data primary key
    protected $keyType = 'int';
    
    // menentukan kolom kolom lain pada tabel (yang bukan primary key)
    protected $fillable = [
        'kode_matkul',
        'nama_matkul',
        'semester',
        'sks',
    ];

     // menentukan relasi tabel 
     public function jadwal()
     {
         return $this->hasOne(Jadwal::class, 'id_matkul', 'id_matkul');
     }
 
     public function berita_acara()
     {
         return $this->hasOne(Berita_acara::class, 'id_jadwal', 'id_dosen');
     }
 }