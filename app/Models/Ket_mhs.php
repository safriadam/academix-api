<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ket_mhs extends Model
{
    use HasFactory;
    
    protected $guarded = [];

    public function ketMhs(){
        return $this->belongsTo(Ket_Mhs::class);
    }
}
