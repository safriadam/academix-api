<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// hubungkan model model yang diperlukan
use app\Models\User;

class Dosen extends Model
{
    use HasFactory;

    // nonaktifkan timestamp karna di tabel tidak memakai kolom 'created_at' & 'updated_at'
    public $timestamps = false;

    // menghubungkan nama tabel dengan model
    protected $table = 'dosens';

    // menentukan primary key pada kolom tabel
    protected $primaryKey = 'id_dosen';

    // menentukan apakah primary key auto increment pada tabel
    public $incrementing = true;

    // menentukan tipe data primary key
    protected $keyType = 'int';
    
    // menentukan kolom kolom lain pada tabel (yang bukan primary key)
    protected $fillable = [
        'user_id',
        'nidn',
        'nip',
        'nama',
        'is_kaprodi',
        'no_hp',
        'foto',
    ];

    // menentukan relasi tabel 
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function mahasiswa()
    {
        return $this->hasOne(Mahasiswa::class, 'id_dosen_PA', 'id_dosen');
    }
}