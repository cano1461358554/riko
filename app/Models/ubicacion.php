<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ubicacion extends Model
{
    use HasFactory;

    protected $fillable = ['ubicacion'];

    public function almacenes()
    {
        return $this->hasMany(Almacen::class);
    }

}




