<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Comment extends Model
{
    protected $table = 'comments';
    protected $fillable = [
        'body',
        'user_id',
        'article_id'
    ];

    // Relationships
    public function user(): BelongsTo {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function article(): BelongsTo {
        return $this->belongsTo(Article::class, 'article_id');
    }
}
