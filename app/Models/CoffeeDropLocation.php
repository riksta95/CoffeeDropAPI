<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CoffeeDropLocation extends Model
{
    use HasFactory;

    protected $fillable = [
        'postcode',
        'longitude',
        'latitude',
        'opening_times',
        'closing_times',
    ];
}
