<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SerializedHistory extends Model
{
    use HasFactory;

    public function userInfo(){
        return $this->belongsTo(User::class,'updated_by','id');
    }
}
