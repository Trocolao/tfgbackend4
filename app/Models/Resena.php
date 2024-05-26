<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Resena extends Model
{
    use HasFactory;
    protected $fillable=['fecha','descripcion','valoracion','titulo'];
    public function user(){
        return $this->belongsTo('App\Models\User');
    }
}
