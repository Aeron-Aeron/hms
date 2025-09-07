<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function doctorProfile()
    {
        return $this->hasOne(DoctorProfile::class)->withDefault([
            'specialization' => 'General Medicine',
            'experience_years' => 0,
            'bio' => 'Profile not yet updated'
        ]);
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class, 'patient_id');
    }

    public function doctorAppointments()
    {
        return $this->hasMany(Appointment::class, 'doctor_id');
    }

    public function healthProblems()
    {
        return $this->hasMany(HealthProblem::class);
    }

    public function ratings()
    {
        return $this->hasMany(DoctorRating::class, 'doctor_id');
    }

    public function givenRatings()
    {
        return $this->hasMany(DoctorRating::class, 'patient_id');
    }

    public function hasRole($role)
    {
        return $this->role === $role;
    }

    /**
     * Returns the doctor's overall rating.
     * Priority:
     * 1. If ratings relation is loaded, compute weighted avg using the relation
     * 2. If a preloaded aggregate exists (ratings_avg_rating), use that
     * 3. Fallback to computing via the DoctorRating helper (DB query)
     */
    public function getOverallRatingAttribute()
    {
        // Overall rating is the simple arithmetic average (1-5)
        if ($this->relationLoaded('ratings')) {
            $ratings = $this->ratings;
            if ($ratings->isEmpty()) {
                return 0;
            }
            return round($ratings->avg('rating'), 1);
        }

        if (isset($this->attributes['ratings_avg_rating'])) {
            return round($this->attributes['ratings_avg_rating'], 1);
        }

        // Fallback: compute via DB (unweighted avg of rating column)
        $avg = \App\Models\DoctorRating::where('doctor_id', $this->id)->avg('rating');
        return $avg ? round($avg, 1) : 0;
    }

    /**
     * Convenient ratings count accessor: use withCount value if present or the relation.
     */
    public function getRatingsCountAttribute()
    {
        if (isset($this->attributes['ratings_count'])) {
            return (int) $this->attributes['ratings_count'];
        }

        if ($this->relationLoaded('ratings')) {
            return $this->ratings->count();
        }

        return $this->ratings()->count();
    }

    /**
     * Weighted rating accessor using DoctorRating::calculateWeightedRating
     */
    public function getWeightedRatingAttribute()
    {
        if ($this->relationLoaded('ratings')) {
            $ratings = $this->ratings;
            if ($ratings->isEmpty()) {
                return 0;
            }
            $weighted = $ratings->map(fn($r) => $r->calculateWeightedRating());
            return round($weighted->avg(), 1);
        }

        // If a precomputed aggregated weighted score exists on the model, use it
        if (isset($this->attributes['weighted_rating'])) {
            return round($this->attributes['weighted_rating'], 1);
        }

        // Fallback to the DB helper which calculates weighted per-rating averages
        return \App\Models\DoctorRating::getOverallDoctorRating($this->id);
    }
}
