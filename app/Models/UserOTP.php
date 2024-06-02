<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class UserOTP extends Model
{
    use HasFactory;

    protected $table = "user_otps";

    protected $fillable = [
        'user_id',
         'otp_code',
         'expire_at',
    ];

    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }
}
