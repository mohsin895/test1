<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DoctorAttribute extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function user()
    {
        return $this->morphTo(User::class);
    }
}
