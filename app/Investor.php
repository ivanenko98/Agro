<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Investor extends Model
{
    protected $fillable = ['name', 'surname', 'phone'];

    public function shipments(){
        return $this->hasMany(Shipment::class);
    }

    public function elevators()
    {
        return $this->belongsToMany('App\Elevator');
    }
}
