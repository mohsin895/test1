<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DoctorSpecialization extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    protected $with = ['specialization'];

    public function specialization()
    {
        return $this->belongsTo(Specialization::class, 'specialization_id', 'id');
    }
    public function doctor()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
