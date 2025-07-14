<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoMaterial extends Model
{
    protected $fillable = ['descripcion'];

    public function materials()
    {
        return $this->hasMany(Material::class, 'tipomaterial_id');
    }
}
