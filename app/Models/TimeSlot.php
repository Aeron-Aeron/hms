<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class TimeSlot extends Model
{
    use HasFactory;

    protected $fillable = [
        'doctor_id',
        'start_time',
        'end_time',
        'duration',
        'is_available',
        'date'
    ];

    protected $casts = [
        'date' => 'date',
        'is_available' => 'boolean',
    ];

    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }

    public function getFormattedTimeAttribute()
    {
        return Carbon::parse($this->start_time)->format('h:i A') . ' - ' .
               Carbon::parse($this->end_time)->format('h:i A');
    }
}
