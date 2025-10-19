<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hashtag extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'posts_count'];

    public function posts()
    {
        return $this->belongsToMany(Post::class, 'post_hashtag');
    }

    public static function extractFromText($text)
    {
        preg_match_all('/#(\w+)/', $text, $matches);
        return $matches[1] ?? [];
    }
}
