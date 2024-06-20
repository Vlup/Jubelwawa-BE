<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = ['read_at'];

    protected $casts = [
        'id' => 'string',
        'data' => 'array'
    ];
}