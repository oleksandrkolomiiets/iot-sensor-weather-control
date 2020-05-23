<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserSensor extends Model
{
    protected $fillable = [
        'name',
        'address',
        'last_celsius',
        'last_fahrenheit',
        'last_humidity',
    ];

    protected $casts = [
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];
}
