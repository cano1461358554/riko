<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ingreso extends Model
{
    protected $perPage = 20;

    protected $fillable = [
        'material_id',
        'user_id',
        'cantidad_ingresada',
        'fecha'
    ];
    protected $dates = [
        'fecha',
        'created_at',
        'updated_at'
    ];

// O mejor aún en versiones más recientes de Laravel:
    protected $casts = [
        'fecha' => 'datetime',
    ];
    public function material()
    {
        return $this->belongsTo(Material::class);
    }

    public function almacen()
    {
        return $this->belongsTo(Almacen::class);
    }

    public function user()
    {
        return $this->belongsTo(user::class);
    }
    public function stock()
    {
        return $this->hasOne(Stock::class);
    }
}
