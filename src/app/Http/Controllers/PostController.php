<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    use AuthorizesRequests;
    public function index()
    {
        // Feed: men follow qilgan userlarning postlari
        $followingIds = auth()->user()->following()->pluck('users.id');

        $posts = Post::whereIn('user_id', $followingIds)
            ->orWhere('user_id', auth()->id())
            ->latest()
            ->with('user', 'comments.user', 'likes')
            ->paginate(15);

        return view('posts.index', compact('posts'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'content' => 'required|string|max:1000',
            'type' => 'required|in:text,image,video,link',
            'image' => 'nullable|image|max:5120',
            'video' => 'nullable|mimes:mp4,mov,avi|max:51200',
            'link_url' => 'nullable|url',
        ]);

        $validated['user_id'] = auth()->id();

        // Handle image upload
        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('posts/images', 'public');
        }

        // Handle video upload
        if ($request->hasFile('video')) {
            $validated['video'] = $request->file('video')->store('posts/videos', 'public');
        }

        // Fetch link preview if URL provided
        if ($validated['type'] === 'link' && !empty($validated['link_url'])) {
            $linkData = $this->fetchLinkPreview($validated['link_url']);
            $validated = array_merge($validated, $linkData);
        }

        $post = Post::create($validated);

        // Extract and attach hashtags
        $post->attachHashtags();

        return redirect()->route('posts.index')
            ->with('success', 'Post created successfully!');
    }


    public function edit(Post $post)
    {
        $this->authorize('update', $post);
        return view('posts.edit', compact('post'));
    }

    public function update(Request $request, Post $post)
    {
        $this->authorize('update', $post);

        $validated = $request->validate([
            'content' => 'required|string|max:1000',
            'image' => 'nullable|image|max:5120',
        ]);

        if ($request->hasFile('image')) {
            if ($post->image) {
                Storage::disk('public')->delete($post->image);
            }
            $validated['image'] = $request->file('image')->store('posts', 'public');
        }

        $post->update($validated);

        return redirect()->route('posts.index')
            ->with('success', 'Post updated successfully!');
    }

    public function destroy(Post $post)
    {
        $this->authorize('delete', $post);

        if ($post->image) {
            Storage::disk('public')->delete($post->image);
        }

        $post->delete();

        return redirect()->route('posts.index')
            ->with('success', 'Post deleted successfully!');
    }
    private function fetchLinkPreview($url)
    {
        try {
            $html = file_get_contents($url);

            // Extract title
            preg_match('/(.*?)<\/title>/is', $html, $title);

            // Extract meta description
            preg_match('/<meta\s+name=["\']description["\']\s+content=["\'](.*?)["\']/is', $html, $description);

            // Extract og:image
            preg_match('/<meta\s+property=["\']og:image["\']\s+content=["\'](.*?)["\']/is', $html, $image);

            return [
                'link_title' => $title[1] ?? '',
                'link_description' => $description[1] ?? '',
                'link_image' => $image[1] ?? '',
            ];
        } catch (\Exception $e) {
            return [
                'link_title' => $url,
                'link_description' => '',
                'link_image' => '',
            ];
        }
    }
}
