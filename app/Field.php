<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Field extends Model
{
    protected $fillable = ['name', 'harvest', 'region_id', 'latitude', 'longitude'];

    public function region(){
        return $this->belongsTo(Region::class);
    }
}
