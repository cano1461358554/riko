<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Devolucion extends Model
{
    protected $table = 'devolucions';

    protected $fillable = [
        'prestamo_id',
        'cantidad_devuelta',
        'fecha_devolucion',
        'descripcion_estado'
    ];

//    protected $casts = [
//        'fecha_devolucion' => 'date',
//        'cantidad_devuelta' => 'decimal:2'
//    ];

    public function prestamo()
    {
        return $this->belongsTo(Prestamo::class);
    }
}
