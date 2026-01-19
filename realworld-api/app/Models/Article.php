<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Article extends Model
{
    protected $table = 'articles';

    protected $fillable = [
        'slug',
        'title',
        'description',
        'body',
        'tag_list',
        'author_id'
    ];

    // Relationships
    public function author(): BelongsTo {
        return $this->belongsTo(User::class, 'author_id');
    }
    public function tags(): BelongsToMany {
        return $this->belongsToMany(
            Tag::class,
            'tags_articles',
            'article_id',
            'tag_id'
        );
    }
    public function comments(): HasMany {
        return $this->hasMany(Comment::class, 'article_id');
    }
    public function favorited(): BelongsToMany {
        return $this->belongsToMany(
            User::class,
            'users_articles_favorites',
            'article_id',
            'user_id'
        )->withTimestamps();
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

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

}
