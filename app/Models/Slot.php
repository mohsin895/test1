<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Slot extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];

    public function appointments() {
        return $this->hasMany(Appointment::class, 'slot_id', 'id');
    }

    public function visitation_tracks() {
        return $this->hasOne(VisitationTrack::class, 'slot_id', 'id');
    }

    public function doctor_chamber() {
        return $this->belongsTo(DoctorChamber::class, 'doctor_chamber_id', 'id');
    }

    public function modifier() {
        return $this->hasMany(SlotModifier::class, 'slot_id', 'id');
    }
}
