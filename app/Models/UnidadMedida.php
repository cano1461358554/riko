<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UnidadMedida extends Model
{
    protected $fillable = ['descripcion_unidad'];

    public function materials()
    {
        return $this->hasMany(Material::class, 'unidadmedida_id');
    }
}
