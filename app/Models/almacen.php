<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Almacen extends Model
{
    use HasFactory;

    protected $fillable = ['nombre', 'ubicacion_id'];

    public function ubicacion()
    {
        return $this->belongsTo(Ubicacion::class);
    }

    public function materials()
    {
        return $this->belongsToMany(Material::class)->withPivot('cantidad');
    }

    public function stocks()
    {
        return $this->hasMany(Stock::class );
    }

    public function ingresos()
    {
        return $this->hasMany(Ingreso::class );
    }

}
