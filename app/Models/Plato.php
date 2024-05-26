<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plato extends Model
{
    use HasFactory;
    protected $fillable = ['nombre', 'descripcion', 'categoria', 'precio'];
    public function pedidos(){
        return $this->belongsToMany('App\Models\Pedidos','pedido_plato')->withPivot('cantidad');
    }
    public function imagenes(){
        return $this->hasMany(Imagene::class);
    }

}
