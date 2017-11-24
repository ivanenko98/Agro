<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
    protected $fillable = ['name', 'radius', 'latitude', 'longitude'];

    public function fields(){
        return $this->hasMany(Field::class);
    }

    public function elevators(){
        return $this->hasMany(Elevator::class);
    }
}
