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

    public function calculateWeightedRating()
    {
        $baseRating = $this->rating;
        $helpfulnessScore = $this->helpful_votes / max($this->total_votes, 1);
        $verificationBonus = $this->verified_appointment ? 0.2 : 0;
        $recencyWeight = $this->calculateRecencyWeight();

        return ($baseRating * 0.6) +
               ($helpfulnessScore * 0.2) +
               ($verificationBonus) +
               ($recencyWeight * 0.2);
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
