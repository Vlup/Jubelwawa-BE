<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    protected $fillable = ['user_id', 'property_id'];
    
    public function property()
    {
        return $this->belongsTo(Property::class);
    }
}
