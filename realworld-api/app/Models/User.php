<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'bio'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // relationships
    public function image() : BelongsTo {
        return $this->belongsTo(Image::class);
    }
    public function followers(): BelongsToMany {
        return $this->belongsToMany(
            User::class,
            'following',
            'following_id',
            'follower_id'
        )->withTimestamps();
    }
    public function following(): BelongsToMany {
        return $this->belongsToMany(
            User::class,
            'following',
            'follower_id',
            'following_id'
        )->withTimestamps();
    }
    public function favorites(): BelongsToMany {
        return $this->belongsToMany(
            Article::class,
            'users_articles_favorites',
            'user_id',
            'article_id'
        )->withTimestamps();
    }
    public function articles(): HasMany {
        return $this->hasMany(
            Article::class,
            'author_id'
        );
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public function getRouteKeyName(): string
    {
        return 'name';
    }
}
