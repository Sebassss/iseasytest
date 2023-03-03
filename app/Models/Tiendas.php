<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tiendas extends Model
{
    use HasFactory;
    protected $fillable = [
        'nombre'
    ];



    public function productos()
    {
        return $this->hasMany(Productos::class, 'tienda_id');
    }



    // public function actions()
    // {
    //     return $this->hasMany(Action::class);
    // }
}
