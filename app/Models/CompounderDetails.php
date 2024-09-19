<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompounderDetails extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function chamber()
    {
        return $this->belongsTo(Chamber::class, 'chamber_id', 'id');
    }
    public function slot()
    {
        return $this->belongsTo(Slot::class, 'slot_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'compounder_id', 'id');
    }
}
