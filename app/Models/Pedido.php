<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    use HasFactory;
    protected  $fillable=['total','fecha'];
    public function user(){
        return $this->belongsTo('App\Models\User');
    }
    public function platos(){
        return $this->belongsToMany('App\Models\Plato','pedido_plato')->withPivot('cantidad');
    }
}
