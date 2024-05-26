<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Evento extends Model
{
    protected $fillable=['nombre','descripcion','fecha','limite_participantes','participantes'];
    use HasFactory;

    public function users(){
        return $this->belongsToMany('App\Models\User','evento_user');
    }
    public function files(){
        return $this->hasMany(File::class);
    }
}
