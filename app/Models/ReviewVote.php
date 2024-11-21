<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReviewVote extends Model
{
    protected $fillable = [
        'doctor_rating_id',
        'user_id',
        'is_helpful'
    ];

    public function rating()
    {
        return $this->belongsTo(DoctorRating::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
