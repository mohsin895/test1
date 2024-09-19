<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected function path(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => url($value),
        );
    }

    protected function realPath(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => url($value),
        );
    }
}
