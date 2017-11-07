<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Elevator extends Model
{
    protected $fillable = ['name', 'capacity', 'farmer_id', 'latitude', 'longitude'];
}
