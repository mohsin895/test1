<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WeekDays extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function doctor() {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
