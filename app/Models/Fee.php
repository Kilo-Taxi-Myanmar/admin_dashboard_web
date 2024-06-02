<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Models\Trip;

class Fee extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'amount',
    ];



    public function trip(){
        return $this->belongsToMany(Trip::class, 'tripandexterfee', 'exter_id', 'trip_id');
    }
}
