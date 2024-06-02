<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'profile_image',
        'front_nrc_image',
        'back_nrc_image',
        'front_license_image',
        'back_license_image',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
