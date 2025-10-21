@extends('layouts.app')

@section('content')
    <div class="max-w-2xl mx-auto px-4">

        <!-- ✏️ Create Post Form -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-md p-5 mb-8 transition hover:shadow-lg">
            <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data" x-data="{ postType: 'text' }">
                @csrf

                <div class="flex items-start space-x-4">
                    <img src="{{ auth()->user()->avatar_url }}"
                         alt="{{ auth()->user()->name }}"
                         class="w-12 h-12 rounded-full ring-2 ring-blue-500 object-cover">

                    <div class="flex-1">
                    <textarea name="content"
                              rows="3"
                              placeholder="What's on your mind? 💭 Use #hashtags"
                              class="w-full border dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 rounded-xl p-3 resize-none focus:ring-2 focus:ring-blue-500 focus:outline-none shadow-sm transition"></textarea>

                        <!-- Post Type Selector -->
                        <div class="mt-3 flex items-center gap-3">
                            <select name="type" x-model="postType"
                                    class="border rounded-lg px-3 py-2 text-sm dark:bg-gray-900 dark:border-gray-700 dark:text-gray-200 focus:ring-blue-500">
                                <option value="text">📝 Text</option>
                                <option value="image">🖼️ Image</option>
                                <option value="video">🎥 Video</option>
                                <option value="link">🔗 Link</option>
                            </select>
                        </div>

                        <!-- Upload Fields -->
                        <div x-show="postType === 'image'" class="mt-3">
                            <label class="block text-sm text-gray-600 dark:text-gray-300 mb-1">Upload Image</label>
                            <input type="file" name="image" accept="image/*"
                                   class="w-full text-sm dark:text-gray-200">
                        </div>

                        <div x-show="postType === 'video'" class="mt-3">
                            <label class="block text-sm text-gray-600 dark:text-gray-300 mb-1">Upload Video</label>
                            <input type="file" name="video" accept="video/*"
                                   class="w-full text-sm dark:text-gray-200">
                            <p class="text-xs text-gray-500 mt-1">Max 50MB</p>
                        </div>

                        <div x-show="postType === 'link'" class="mt-3">
                            <label class="block text-sm text-gray-600 dark:text-gray-300 mb-1">Paste URL</label>
                            <input type="url" name="link_url"
                                   placeholder="https://example.com"
                                   class="w-full border dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                        </div>

                        <!-- Submit -->
                        <div class="mt-4 flex justify-end">
                            <button type="submit"
                                    class="bg-blue-600 text-white px-6 py-2 rounded-full font-medium hover:bg-blue-700 transition">
                                🚀 Post
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- 📰 Posts Feed -->
        @foreach($posts as $post)
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm mb-6 overflow-hidden border border-gray-100 dark:border-gray-700 hover:shadow-lg transition">

                <!-- Header -->
                <div class="p-4 flex justify-between items-center">
                    <div class="flex items-center space-x-3">
                        <img src="{{ $post->user->avatar_url }}" class="w-10 h-10 rounded-full object-cover">
                        <div>
                            <a href="{{ route('profile.show', $post->user->username) }}"
                               class="font-semibold text-gray-800 dark:text-gray-200 hover:underline">
                                {{ $post->user->name }}
                            </a>
                            <p class="text-xs text-gray-500">{{ $post->created_at->diffForHumans() }}</p>
                        </div>
                    </div>

                    @if($post->user_id === auth()->id())
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                                ⋮
                            </button>
                            <div x-show="open"
                                 @click.away="open = false"
                                 class="absolute right-0 mt-2 w-32 bg-white dark:bg-gray-900 rounded-lg shadow-lg border dark:border-gray-700 z-10">
                                <a href="{{ route('posts.edit', $post) }}"
                                   class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">Edit</a>
                                <form action="{{ route('posts.destroy', $post) }}" method="POST">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                            class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100 dark:hover:bg-gray-700"
                                            onclick="return confirm('Are you sure?')">Delete</button>
                                </form>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Content -->
                <div class="px-4 pb-4 text-gray-800 dark:text-gray-100">
                    {!! preg_replace('/#(\w+)/', '<a href="/hashtag/$1" class="text-blue-600 hover:underline">#$1</a>', e($post->content)) !!}
                </div>

                <!-- Media -->
                @if($post->type === 'image' && $post->image)
                    <img src="{{ $post->image_url }}" class="w-full max-h-[450px] object-cover">
                @elseif($post->type === 'video' && $post->video)
                    <video controls class="w-full max-h-[450px] object-cover">
                        <source src="{{ $post->video_url }}" type="video/mp4">
                    </video>
                @elseif($post->type === 'link' && $post->link_url)
                    <a href="{{ $post->link_url }}" target="_blank"
                       class="block hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                        @if($post->link_image)
                            <img src="{{ $post->link_image }}" class="w-full object-cover max-h-52">
                        @endif
                        <div class="p-4">
                            <h3 class="font-semibold text-gray-900 dark:text-gray-200">{{ $post->link_title }}</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400">{{ Str::limit($post->link_description, 100) }}</p>
                            <span class="text-xs text-gray-400 mt-2 block">{{ parse_url($post->link_url, PHP_URL_HOST) }}</span>
                        </div>
                    </a>
                @endif

                <!-- Stats -->
                <div class="px-4 py-2 border-t border-gray-100 dark:border-gray-700 flex justify-between text-sm text-gray-500">
                    <span>{{ $post->likes_count }} ❤️</span>
                    <span>{{ $post->comments_count }} 💬</span>
                </div>

                <!-- Actions -->
                <div class="px-4 py-2 flex items-center gap-6 text-sm border-t border-gray-100 dark:border-gray-700">
                    <button onclick="likePost({{ $post->id }})"
                            class="flex items-center gap-2 text-gray-600 dark:text-gray-300 hover:text-blue-600"
                            data-post-id="{{ $post->id }}"
                            data-liked="{{ $post->isLikedBy(auth()->user()) ? 'true' : 'false' }}">
                        <svg class="w-5 h-5 {{ $post->isLikedBy(auth()->user()) ? 'text-blue-600 fill-current' : '' }}"
                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                        </svg>
                        <span>Like</span>
                    </button>

                    <button class="flex items-center gap-2 text-gray-600 dark:text-gray-300 hover:text-blue-600">
                        💬 <span>Comment</span>
                    </button>
                </div>

                <!-- Comments -->
                <div class="px-4 pb-4 border-t border-gray-100 dark:border-gray-700">
                    @foreach($post->comments as $comment)
                        <div class="flex items-start space-x-3 mt-3">
                            <img src="{{ $comment->user->avatar_url }}" class="w-8 h-8 rounded-full">
                            <div class="flex-1 bg-gray-100 dark:bg-gray-900 rounded-xl p-3">
                                <a href="{{ route('profile.show', $comment->user->username) }}"
                                   class="font-semibold text-sm text-gray-800 dark:text-gray-200 hover:underline">
                                    {{ $comment->user->name }}
                                </a>
                                <p class="text-sm text-gray-700 dark:text-gray-300">{{ $comment->content }}</p>
                                <div class="text-xs text-gray-500 mt-1 flex justify-between">
                                    <span>{{ $comment->created_at->diffForHumans() }}</span>
                                    @if($comment->user_id === auth()->id())
                                        <form action="{{ route('comments.destroy', $comment) }}" method="POST">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-red-500 hover:underline">Delete</button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach

                    <!-- Add Comment -->
                    <form action="{{ route('comments.store', $post) }}" method="POST" class="mt-3 flex items-start gap-3">
                        @csrf
                        <img src="{{ auth()->user()->avatar_url }}" class="w-8 h-8 rounded-full">
                        <div class="flex-1 bg-gray-100 dark:bg-gray-900 rounded-full flex items-center px-3 py-2">
                            <input name="content" type="text" placeholder="Write a comment..."
                                   class="flex-1 bg-transparent border-0 text-sm dark:text-gray-200 focus:ring-0 outline-none" required>
                            <button type="submit" class="bg-blue-600 text-white text-xs px-4 py-1.5 rounded-full hover:bg-blue-700 transition">
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
                fetch(`/posts/${postId}/like`, {
                    method: isLiked ? 'DELETE' : 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    }
                })
                    .then(() => location.reload());
            }
        </script>
    @endpush
@endsection
