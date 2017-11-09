<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tool extends Model
{
    protected $fillable = ['factory_id', 'name', 'description'];

    public function factory(){
        return $this->belongsTo(Factory::class);
    }
}
