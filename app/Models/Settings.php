<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Settings extends Model
{
    use HasFactory;

    protected $with = [
        'logo_light',
        'logo_dark',
        'fave_icon',
    ];

    protected $guarded = ['id'];

    public function logo_light()
    {
        return $this->belongsTo(Media::class, 'logo_light', 'id');
    }

    public function logo_dark()
    {
        return $this->belongsTo(Media::class, 'logo_dark', 'id');
    }

    public function fave_icon()
    {
        return $this->belongsTo(Media::class, 'fave_icon', 'id');
    }
}
