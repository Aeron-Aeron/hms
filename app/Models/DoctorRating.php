<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DoctorRating extends Model
{
    use HasFactory;

    protected $fillable = [
        'doctor_id',
        'patient_id',
        'appointment_id',
        'rating',
        'review',
        'helpful_votes',
        'total_votes',
        'verified_appointment',
        'review_date'
    ];

    protected $casts = [
        'review_date' => 'datetime',
        'verified_appointment' => 'boolean'
    ];

    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }

    public function patient()
    {
        return $this->belongsTo(User::class, 'patient_id');
    }

    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }

    public function reviewVotes()
    {
        return $this->hasMany(\App\Models\ReviewVote::class, 'doctor_rating_id');
    }

    public function calculateWeightedRating()
    {
    // Weights (must sum to 1): baseRating 0.6, helpfulness 0.2, verification 0.1, recency 0.1
    $baseRating = $this->rating; // 1-5
    $helpfulnessScore = $this->helpful_votes / max($this->total_votes, 1); // 0-1
    $recencyWeight = $this->calculateRecencyWeight(); // 0-1

    // Map 0-1 components to 1-5 scale by multiplying by 5 where appropriate
    $weighted = ($baseRating * 0.6) +
           ($helpfulnessScore * 5 * 0.2) +
           (($this->verified_appointment ? 5 : 0) * 0.1) +
           ($recencyWeight * 5 * 0.1);

    // Ensure value is within 0-5 and return
    return max(0, min(5, $weighted));
    }

    private function calculateRecencyWeight()
    {
        $ageInDays = $this->review_date->diffInDays(now());
        return max(0, 1 - ($ageInDays / 365)); // Decay over a year
    }

    public static function getOverallDoctorRating($doctorId)
    {
        $ratings = self::where('doctor_id', $doctorId)
            ->get()
            ->map(function ($rating) {
                return $rating->calculateWeightedRating();
            });

        return $ratings->count() > 0 ? round($ratings->avg(), 1) : 0;
    }
}
