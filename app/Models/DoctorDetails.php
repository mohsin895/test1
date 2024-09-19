<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DoctorDetails extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'specialization_ids' => 'array',
        'designation_ids' => 'array',
        'degree_info' => 'array',
        'weekdays' => 'array',
    ];

    public function doctor()
    {
        return $this->belongsTo(User::class, 'id', 'user_id');
    }
}
