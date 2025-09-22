<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Article extends Model
{
    protected $table = 'articles';

    // Relationships
    public function author(): BelongsTo {
        return $this->belongsTo(User::class, 'author_id');
    }
    public function tags(): HasMany {
        return $this->hasMany(Tag::class);
    }
    public function favorited(): BelongsToMany {
        return $this->belongsToMany(
            User::class,
            'users_articles_favorites',
            'article_id',
            'user_id'
        );
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array {
        return [
            'tag_list' => 'array'
        ];
    }

}
