<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Post;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->input('q');

        if (empty($query)) {
            return view('search.index', [
                'users' => collect(),
                'posts' => collect(),
                'query' => ''
            ]);
        }

        // Meilisearch orqali qidirish
        $users = User::search($query)->take(10)->get();
        $posts = Post::search($query)->take(20)->get()->load('user');

        return view('search.index', compact('users', 'posts', 'query'));
    }
}
