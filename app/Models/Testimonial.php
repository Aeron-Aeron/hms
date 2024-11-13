<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'comment',
        'rating',
        'is_featured'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
