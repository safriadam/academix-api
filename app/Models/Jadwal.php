<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// hubungkan model model yang diperlukan
use app\Models\matkul;

class Jadwal extends Model
{
    use HasFactory;

     // nonaktifkan timestamp karna di tabel tidak memakai kolom 'created_at' & 'updated_at'
     public $timestamps = false;

     // menghubungkan nama tabel dengan model
     protected $table = 'jadwal';
 
     // menentukan primary key pada kolom tabel
     protected $primaryKey = 'id_jadwal';
 
     // menentukan apakah primary key auto increment pada tabel
     public $incrementing = false;
 
     // menentukan tipe data primary key
     protected $keyType = 'int';
     
     // menentukan kolom kolom lain pada tabel (yang bukan primary key)
     protected $fillable = [
         'id_jadwal',
         'id_kelas',
         'id_matkul',
         'ruang',
         'hari',
         'start',
         'finish',
         'jumlah_jam',
         'token',
     ];

     // menentukan relasi tabel 
    public function matkul()
    {
        return $this->belongsTo(Matkul::class, 'id_matkul', 'id_matkul');
    }

    public function berita_acara()
    {
        return $this->hasOne(Berita_acara::class, 'id_jadwal', 'id_jadwal');
    }
}