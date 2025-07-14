<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Stock extends Model
{
    use SoftDeletes;

    protected $fillable = ['material_id', 'almacen_id', 'cantidad'];

//    public function material()
//    {
//        return $this->belongsTo(Material::class);
//    }
// En tu modelo Stock.php
    public function material()
    {
        return $this->belongsTo(Material::class)->withTrashed();
    }
    public function almacen()
    {
        return $this->belongsTo(Almacen::class);
    }
}
