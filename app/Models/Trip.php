<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Model;
use App\Models\CarType;
use App\Models\User;
use App\Models\Fee;


class Trip extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'distance',
        'duration',
        'waiting_time',

        'normal_fee',
        'waiting_fee',
        'extra_fee',
        'total_cost',
        'driver_id',


        'start_lat',
        'start_lng',
        'end_lat',
        'end_lng',
        'start_address',
        'end_address',
        'cartype',
        'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class,"driver_id");
    }

    public function cartype()
    {
        return $this->hasOne(CarType::class);
    }

    public function exterfees(){
        return $this->belongsToMany(Fee::class, 'tripandexterfee', 'trip_id', 'exter_id');
       
    }
}
