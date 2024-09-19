<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $with = ['patientType', 'media_file'];

    public function media_file() {
        return $this->belongsTo(Media::class, 'media', 'id');
    }
    public function media() {
        return $this->belongsTo(Media::class, 'media', 'id');
    }

    public function slot() {
        return $this->belongsTo(Slot::class, 'slot_id', 'id');
    }

    public function doctor() {
        return $this->belongsTo(User::class, 'doctor_id', 'id');
    }

    public function chamber() {
        return $this->belongsTo(Chamber::class, 'chamber_id', 'id');
    }

    public function doctor_chamber() {
        return $this->belongsTo(DoctorChamber::class, 'chamber_id', 'id');
    }

    public function patientType()
    {
        return $this->belongsTo(PatientType::class, 'patient_type_id', 'id');
    }
    public function serializedStatus(){
   return $this->hasMany(SerializedHistory::class,);
    }
}
