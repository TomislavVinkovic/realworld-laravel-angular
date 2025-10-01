<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Tag extends Model
{
    protected $table = 'tags';

    protected $fillable = ['tag'];

    public function articles(): BelongsToMany {
        return $this->belongsToMany(
            Article::class,
            'tags_articles',
            'tag_id',
            'article_id'
        );
    }
}
