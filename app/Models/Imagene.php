<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Imagene extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'plato_id',
        'file_path',

    ];
    public function plato(){
        return $this-> belongsTo(Plato::class);
    }
}
