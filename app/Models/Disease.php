<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Disease extends Model
{
    protected $fillable = ['name'];

    public function symptoms()
    {
        return $this->belongsToMany(Symptom::class, 'disease_symptom');
    }
}
