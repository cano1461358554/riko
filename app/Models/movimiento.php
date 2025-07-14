<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Movimiento
 *
 * @property $id
 * @property $cantidad
 * @property $fecha
 * @property $created_at
 * @property $updated_at
 *
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Movimiento extends Model
{

    protected $perPage = 20;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['cantidad', 'fecha'];
    public function material()
    {
        return $this->belongsTo(Material::class );
    }

//    public function tipoMovimiento()
//    {
//        return $this->belongsTo(TipoMovimiento::class );
//    }

    public function prestamo()
    {
        return $this->hasOne(Prestamo::class );
    }


}
