<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DoctorChamber extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    protected $casts = [
        'weekdays' => 'array',
    ];
    protected $with = ['chamber'];

    public function chamber()
    {
        return $this->belongsTo(Chamber::class, 'chamber_id', 'id');
    }

    public function doctor()
    {
        return $this->belongsTo(User::class, 'id', 'user_id');
    }

    public function slots()
    {
        return $this->hasMany(Slot::class, 'doctor_chamber_id', 'id');
    }
}
