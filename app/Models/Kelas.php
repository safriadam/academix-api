<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Mahasiswa;
use App\Models\Jadwal;

class Kelas extends Model
{
    use HasFactory;

    // nonaktifkan timestamp karna di tabel tidak memakai kolom 'create_at' & 'update_at'
    public $timestamps = false;

    // menghubungkan nama tabel dengan model
    protected $table = 'kelas';

    // menentukan primary key pada kolom tabel
    protected $primaryKey = 'id_kelas';

    // menentukan apakah primary key auto icrement pada tabel
    public $incrementing = true;

    // menentukan tipe data primary key
    protected $ketType = 'int';

    // menentukan kolom kolom lain pada tabel (yang bukan primary key)
    protected $fillable = [
        'abjad_kls',
        'smt',
    ];

    // menentukan relasi tabel
    public function mahasiswa()
    {
        return $this->hasOne(Mahasiswa::class,'id_kelas','id_kelas');

    }
    public function jadwal()
    {
        return $this->hasOne(Jadwal::class,'id_kelas','id_kelas');
    }

}