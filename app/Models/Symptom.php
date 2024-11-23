<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Symptom extends Model
{
    protected $fillable = ['name'];

    public function diseases()
    {
        return $this->belongsToMany(Disease::class);
    }
}
