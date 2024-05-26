<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reserva extends Model
{
    use HasFactory;
    protected $fillable=['numero_comensales','dia','hora','turno','user_id'];
    public function user(){
        return $this->belongsTo('App\Models\User');
    }

}
