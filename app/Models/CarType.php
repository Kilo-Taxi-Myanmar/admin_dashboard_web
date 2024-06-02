<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Trip;
use App\Models\Vehicle;

class CarType extends Model
{
    use HasFactory;
    protected $table = 'cartype';
    protected $fillable = [
        'type',
        
    ];

    public function vehicle(){
        return $this->belongsTo(Vehicle::class);
    }

    public function trip(){
        return $this->belongsTo(Trip::class);
    }
}

