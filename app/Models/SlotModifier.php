<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SlotModifier extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'created_at' => 'datetime:Y-M-d H:ia',
    ];

    public function user() {
        return $this->belongsTo(User::class, 'doctor_id', 'id');
    }

    public function slot() {
        return $this->belongsTo(Slot::class, 'slot_id', 'id');
    }
}
