<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use App\Models\Trip;

use App\Models\UserOTP;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    public $total_trips;

    public function setTotalTrips($startDate, $endDate)
    {
        if ($startDate == null || $endDate == null) {
            $this->total_trips = $this->trips()->count();
        } else {
            $this->total_trips = $this->trips()->whereBetween('created_at', [$startDate, $endDate])->count();
        }
    }
    protected $fillable = [
        'name',
        'driver_id',
        'email',
        'phone',
        'birth_date',
        'address',
        'nrc_no',
        'driving_license',
        'balance',
        'status',
        'password',
        'device_token'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function userImage()
    {
        return $this->hasOne(UserImage::class);
    }

    public function vehicle()
    {
        return $this->hasOne(Vehicle::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function trips()
    {
        return $this->hasMany(Trip::class,'driver_id');
    }
    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    // public function user(){
    //     return $this->hasMany(User::class,'driver_id');
    // }

    public function userotp(){
        return $this->hasOne(UserOTP::class,'user_id');
    }
}
