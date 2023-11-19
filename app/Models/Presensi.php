<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Presensi extends Model
{
    use HasFactory;

    protected $guarded = [];
    public function Presensi(){
        return $this->belongsTo( Presensi::class);
    }
    protected $primaryKey = 'id_presensi';
}
