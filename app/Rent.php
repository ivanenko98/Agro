<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Rent extends Model
{
    protected $fillable = ['investor_id', 'elevator_id', 'capacity'];
}
