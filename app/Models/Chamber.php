<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chamber extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function hospital() {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function doctor_chamber()
    {
        return $this->hasMany(DoctorChamber::class, 'chamber_id', 'id');
    }
    
    public function compounders()
    {
        return $this->hasMany(CompounderDetails::class, 'id', 'chamber_id');
    }
}
