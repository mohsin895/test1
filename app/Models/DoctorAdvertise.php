<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DoctorAdvertise extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    protected $with = ['doctor_detail'];
    // protected $casts = [
    //     'start_at' => 'datetime:d M, Y',
    //     'end_at' => 'datetime:d M, Y',
    // ];
    public function advertise()
    {
        return $this->belongsTo(Advertising::class, 'advertise_id', 'id');
    }

    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id', 'id');
    }
    
    public function doctor_detail()
    {
        return $this->belongsTo(User::class, 'doctor_id', 'id');
    }
}
