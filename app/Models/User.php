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
        return $this->hasOne(DoctorProfile::class);
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
}
