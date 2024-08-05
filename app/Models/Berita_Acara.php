<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// hubungkan model model yang diperlukan
use app\Models\jadwal;
// use app\Models\matkul;

class Berita_acara extends Model
{
    use HasFactory;

     // nonaktifkan timestamp karna di tabel tidak memakai kolom 'created_at' & 'updated_at'
     public $timestamps = false;

     // menghubungkan nama tabel dengan model
     protected $table = 'berita_acara';
     
     // menentukan kolom kolom lain pada tabel (yang bukan primary key)
     protected $fillable = [
         'id_jadwal',
         'id_dosen',
         'tanggal',
         'ppk_bhsn',
         'spkk_bhsn',
         'media',
         'jam_ajar',
     ];

      // menentukan relasi tabel 
    public function jadwal()
    {
        return $this->belongsTo(Jadwal::class, 'id_jadwal', 'id_jadwal');
    }

}