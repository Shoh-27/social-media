<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Post extends Model
{
    use HasFactory, Searchable;

    protected $fillable = [
        'user_id',
        'content',
        'image',
        'likes_count',
        'comments_count',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    // Searchable
    public function toSearchableArray()
    {
        return [
            'content' => $this->content,
            'user_name' => $this->user->name,
        ];
    }

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    // Helper methods
    public function isLikedBy(User $user)
    {
        return $this->likes()->where('user_id', $user->id)->exists();
    }

    public function like(User $user)
    {
        if (!$this->isLikedBy($user)) {
            $this->likes()->create(['user_id' => $user->id]);
            $this->increment('likes_count');
        }
    }

    public function unlike(User $user)
    {
        $this->likes()->where('user_id', $user->id)->delete();
        $this->decrement('likes_count');
    }

    public function getImageUrlAttribute()
    {
        return $this->image ? asset('storage/' . $this->image) : null;
    }
}
