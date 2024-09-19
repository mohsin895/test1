<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Advertising extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'special_medicine' => 'array',
        'media' => 'array',
    ];

    public function doctor_advertise()
    {
        return $this->hasMany(DoctorAdvertise::class, 'advertise_id', 'id');
    }

    public function created_by() {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

}
