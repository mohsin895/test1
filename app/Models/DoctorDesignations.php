<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DoctorDesignations extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function doctor_designation()
    {
        return $this->belongsTo(Designation::class, 'designation_id', 'id');
    }
}
