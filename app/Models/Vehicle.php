<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    use HasFactory;

    protected $fillable = ['vehicle_plate_no', 'vehincle_model', 'vehicle_image_url', 'user_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function cartypes(){
        return $this->hasMany(CarType::class);
    }

    
}
