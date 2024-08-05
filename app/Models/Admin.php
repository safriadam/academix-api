<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\User;

class Admin extends Model
{
    use HasFactory;
    // nonaktifkan timestamp karna di tabel tidak memakai kolom 'created_at' & 'updated_at'
    public $timestamps = false;

   // menghubungkan nama tabel dengan model
    protected $table = 'admin';

    // menentukan primary key pada kolom tabel
    protected $primaryKey = 'id_staff';

    // menentukan apakah primary key auto increment pada tabel
    public $incrementing = false;

    // menentukan tipe data primary key
    protected $keyType = 'int';

    // menentukan kolom kolom lain pada tabel (yang bukan primary key)
    protected $fillable = [
        'user_id',
        'nama',
        'no_induk',
        'pwd',
        'no_hp',
    ];

    // menentukan relasi tabel
    public function users()
    {
        return $this ->hasOne(User::class, 'user_id', 'id');
    }


}