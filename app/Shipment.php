<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Shipment extends Model
{
    protected $fillable = ['name', 'volume', 'investor_id', 'latitude', 'longitude'];

    public function investor(){
        return $this->belongsTo(Investor::class);
    }
}
