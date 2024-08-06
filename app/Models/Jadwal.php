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
    //  public $timestamps = false;

     // menghubungkan nama tabel dengan model
     protected $table = 'jadwals';
 
     // menentukan primary key pada kolom tabel
     protected $primaryKey = 'id_jdwl';
 
     // menentukan apakah primary key auto increment pada tabel
     public $incrementing = false;
 
     // menentukan tipe data primary key
     protected $keyType = 'int';
     
     // menentukan kolom kolom lain pada tabel (yang bukan primary key)
     protected $fillable = [
         'id_jadwal',
         'id_kls',
         'id_mk',
         'ruang',
         'hari',
         'start',
         'finish',
         'jumlah_jam',
         'token',
         'created_at',
         'updated_at'
     ];

     // menentukan relasi tabel 
    public function matkul()
    {
        return $this->belongsTo(Matkul::class, 'id_mk', 'id_mk');
    }

    public function berita_acara()
    {
        return $this->hasOne(Berita_acara::class, 'id_jdwl', 'id_jdwl');
    }
}