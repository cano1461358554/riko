<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class TipoMovimiento
 *
 * @property $id
 * @property $descripcion
 * @property $created_at
 * @property $updated_at
 *
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class TipoMovimiento extends Model
{

    protected $perPage = 20;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['descripcion'];
    public function movimientos()
    {
        return $this->hasMany(Movimiento::class );
    }


}
