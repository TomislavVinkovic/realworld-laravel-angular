<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Image extends Model
{
    protected $table = 'images';

    protected $fillable = [
        'src'
    ];

    protected function publicUrl(): Attribute {
        return Attribute::make(
            get: fn ($_, $attributes) => asset(Storage::url($attributes['src']))
        );
    }
}
