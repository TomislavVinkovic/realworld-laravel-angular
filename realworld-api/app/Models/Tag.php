<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Tag extends Model
{
    protected $table = 'tags';
    public function articles(): BelongsTo {
        return $this->belongsTo(Article::class);
    }
}
