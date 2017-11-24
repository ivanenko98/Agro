<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Elevator extends Model
{
    protected $fillable = ['name', 'capacity', 'farmer_id', 'latitude', 'longitude'];

    public function farmer(){
        return $this->belongsTo(Farmer::class);
    }

    public function investors()
    {
        return $this->belongsToMany('App\Investor');
    }

    public function region(){
        return $this->belongsTo(Region::class);
    }
}
