<?php

namespace App\Http\Controllers;

use App\Models\Hashtag;
use Illuminate\Http\Request;

class HashtagController extends Controller
{
    public function show($name)
    {
        $hashtag = Hashtag::where('name', strtolower($name))->firstOrFail();
        $posts = $hashtag->posts()
            ->latest()
            ->with('user', 'comments.user', 'hashtags')
            ->paginate(15);

        return view('hashtags.show', compact('hashtag', 'posts'));
    }

    public function trending()
    {
        $hashtags = Hashtag::orderBy('posts_count', 'desc')
            ->take(10)
            ->get();

        return view('hashtags.trending', compact('hashtags'));
    }
}
