<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Factory extends Model
{
    protected $fillable = ['name', 'latitude', 'longitude'];

    public function tools(){
        return $this->hasMany(Tool::class);
    }
}
