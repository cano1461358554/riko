<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Prestamo extends Model
{
    protected $perPage = 20;
    protected $table = 'prestamos';
    use SoftDeletes;
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'fecha_prestamo',
        'cantidad_prestada',
        'material_id',
        'user_id',
        'descripcion'
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
//    public function material()
//{
//    return $this->belongsTo(Material::class, 'material_id');
//}
    public function material()
    {
        return $this->belongsTo(Material::class)->withTrashed();
    }

    public function devolucions()
    {
        return $this->hasMany(Devolucion::class);
    }





//    public function user()
//    {
//        return $this->belongsTo(User::class);
//    }

//    public function resguardo()
//    {
//        return $this->hasOne(Resguardo::class);
//    }
//
//    public function getCantidadPendienteAttribute()
//    {
//        return $this->cantidad_prestada - $this->devolucions->sum('cantidad_devuelta');
//    }
//
//    public function getCompletamenteDevueltoAttribute()
//    {
//        return $this->cantidad_pendiente <= 0;
//    }



}
