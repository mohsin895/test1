<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DoctorDegree extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function doctor_degrees()
    {
        return $this->belongsTo(Degree::class, 'degree_id', 'id');
    }
}
