@extends('layouts.app')

@section('content')
    <div class="max-w-2xl mx-auto px-4">
        <!-- Create Post Form -->
        <div class="bg-white rounded-lg shadow p-4 mb-6">
            <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="flex items-start space-x-3">
                    <img src="{{ auth()->user()->avatar_url }}"
                         alt="{{ auth()->user()->name }}"
                         class="w-10 h-10 rounded-full">

                    <div class="flex-1">
                    <textarea name="content"
                              rows="3"
                              class="w-full border rounded-lg p-3 focus:outline-none focus:ring-2 focus:ring-blue-500"
                              placeholder="What's on your mind?"></textarea>

                        <div class="mt-3 flex items-center justify-between">
                            <input type="file" name="image" accept="image/*" class="text-sm">
                            <button type="submit"
                                    class="bg-blue-600 text-gray-600 px-6 py-2 rounded-full hover:bg-blue-700">
                                Post
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- Posts Feed -->
        @foreach($posts as $post)
            <div class="bg-white rounded-lg shadow mb-4">
                <!-- Post Header -->
                <div class="p-4 flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <img src="{{ $post->user->avatar_url }}"
                             alt="{{ $post->user->name }}"
                             class="w-10 h-10 rounded-full">
                        <div>
                            <a href="{{ route('profile.show', $post->user->username) }}"
                               class="font-semibold hover:underline">
                                {{ $post->user->name }}
                            </a>
                            <p class="text-sm text-gray-500">{{ $post->created_at->diffForHumans() }}</p>
                        </div>
                    </div>

                    @if($post->user_id === auth()->id())
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" class="text-gray-500 hover:text-gray-700">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"/>
                                </svg>
                            </button>
                            <div x-show="open"
                                 @click.away="open = false"
                                 class="absolute right-0 mt-2 w-32 bg-white rounded-md shadow-lg py-1 z-10">
                                <a href="{{ route('posts.edit', $post) }}"
                                   class="block px-4 py-2 text-sm hover:bg-gray-100">Edit</a>
                                <form action="{{ route('posts.destroy', $post) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100"
                                            onclick="return confirm('Are you sure?')">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Post Content -->
                <div class="px-4 pb-3">
                    <p class="text-gray-800 whitespace-pre-wrap">{{ $post->content }}</p>
                </div>

                <!-- Post Image -->
                @if($post->image)
                    <img src="{{ $post->image_url }}"
                         alt="Post image"
                         class="w-full">
                @endif

                <!-- Post Stats -->
                <div class="px-4 py-2 border-t border-b flex items-center justify-between text-sm text-gray-600">
                    <span>{{ $post->likes_count }} likes</span>
                    <span>{{ $post->comments_count }} comments</span>
                </div>

                <!-- Post Actions -->
                <div class="px-4 py-2 flex items-center space-x-4">
                    <button onclick="likePost({{ $post->id }})"
                            class="like-btn flex items-center space-x-2 text-gray-600 hover:text-blue-600"
                            data-post-id="{{ $post->id }}"
                            data-liked="{{ $post->isLikedBy(auth()->user()) ? 'true' : 'false' }}">
                        <svg class="w-5 h-5 {{ $post->isLikedBy(auth()->user()) ? 'text-blue-600 fill-current' : '' }}"
                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                        </svg>
                        <span>Like</span>
                    </button>

                    <button class="flex items-center space-x-2 text-gray-600 hover:text-blue-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                        </svg>
                        <span>Comment</span>
                    </button>
                </div>

                <!-- Comments Section -->
                <div class="px-4 pb-4 border-t">
                    @foreach($post->comments as $comment)
                        <div class="flex items-start space-x-3 mt-3">
                            <img src="{{ $comment->user->avatar_url }}"
                                 alt="{{ $comment->user->name }}"
                                 class="w-8 h-8 rounded-full">
                            <div class="flex-1 bg-gray-100 rounded-lg p-3">
                                <a href="{{ route('profile.show', $comment->user->username) }}"
                                   class="font-semibold text-sm hover:underline">
                                    {{ $comment->user->name }}
                                </a>
                                <p class="text-sm text-gray-800">{{ $comment->content }}</p>
                                <div class="flex items-center space-x-4 mt-1 text-xs text-gray-500">
                                    <span>{{ $comment->created_at->diffForHumans() }}</span>
                                    @if($comment->user_id === auth()->id())
                                        <form action="{{ route('comments.destroy', $comment) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="text-red-600 hover:underline"
                                                    onclick="return confirm('Delete comment?')">
                                                Delete
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach

                    <!-- Add Comment Form -->
                        <!-- Add Comment Form -->
                        <form action="{{ route('comments.store', $post) }}" method="POST" class="mt-3 flex items-start space-x-3">
                            @csrf
                            <img src="{{ auth()->user()->avatar_url }}"
                                 alt="{{ auth()->user()->name }}"
                                 class="w-8 h-8 rounded-full">

                            <div class="flex-1 flex items-center bg-gray-100 rounded-full px-3 py-2">
                                <input type="text"
                                       name="content"
                                       placeholder="Write a comment..."
                                       class="flex-1 bg-gray-100 border-0 focus:ring-0 focus:outline-none text-sm"
                                       required>

                                <button type="submit"
                                        class="ml-2 bg-gray-500 text-white text-sm px-4 py-1.5 rounded-full hover:bg-blue-700 transition">
                                    Send
                                </button>
                            </div>
                        </form>
                </div>
            </div>
        @endforeach

        <!-- Pagination -->
        <div class="mt-6">
            {{ $posts->links() }}
        </div>
    </div>

    @push('scripts')
        <script>
            function likePost(postId) {
                const btn = document.querySelector(`[data-post-id="${postId}"]`);
                const isLiked = btn.dataset.liked === 'true';
                const url = isLiked
                    ? `/posts/${postId}/like`
                    : `/posts/${postId}/like`;
                const method = isLiked ? 'DELETE' : 'POST';

                fetch(url, {
                    method: method,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    }
                })
                    .then(res => res.json())
                    .then(data => {
                        btn.dataset.liked = !isLiked;
                        if (!isLiked) {
                            btn.querySelector('svg').classList.add('text-blue-600', 'fill-current');
                        } else {
                            btn.querySelector('svg').classList.remove('text-blue-600', 'fill-current');
                        }
                        // Update like count
                        location.reload();
                    });
            }
        </script>
    @endpush
@endsection
