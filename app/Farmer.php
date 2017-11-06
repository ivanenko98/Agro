<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Farmer extends Model
{
    protected $fillable = ['name', 'surname'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function elevators(){
        return $this->hasMany(Elevator::class);
    }
}
