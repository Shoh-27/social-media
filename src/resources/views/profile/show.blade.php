@extends('layouts.app')

@section('content')
    <div class="max-w-4xl mx-auto px-4">
        <!-- Profile Header -->
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <div class="flex items-start space-x-6">
                <img src="{{ $user->avatar_url }}"
                     alt="{{ $user->name }}"
                     class="w-32 h-32 rounded-full">

                <div class="flex-1">
                    <div class="flex items-center justify-between">
                        <div>
                            <h1 class="text-2xl font-bold">{{ $user->name }}</h1>
                            <p class="text-gray-600">{{ $user->username }}</p>
                        </div>

                        @if(auth()->id() === $user->id)
                            <a href="{{ route('profile.edit') }}"
                               class="bg-gray-200 px-4 py-2 rounded-lg hover:bg-gray-300">
                                Edit Profile
                            </a>
                        @else
                            <button onclick="toggleFollow({{ $user->id }})"
                                    id="follow-btn-{{ $user->id }}"
                                    class="px-6 py-2 rounded-lg {{ auth()->user()->isFollowing($user) ? 'bg-gray-200 hover:bg-gray-300' : 'bg-blue-600 text-white hover:bg-blue-700' }}">
                                {{ auth()->user()->isFollowing($user) ? 'Unfollow' : 'Follow' }}
                            </button>
                        @endif
                    </div>

                    <div class="mt-4 flex items-center space-x-6 text-sm">
                        <div>
                            <span class="font-semibold">{{ $user->posts->count() }}</span> posts
                        </div>
                        <div>
                            <span class="font-semibold">{{ $user->followers->count() }}</span> followers
                        </div>
                        <div>
                            <span class="font-semibold">{{ $user->following->count() }}</span> following
                        </div>
                    </div>

                    @if($user->bio)
                        <p class="mt-4 text-gray-800">{{ $user->bio }}</p>
                    @endif

                    @if($user->website)
                        <a href="{{ $user->website }}"
                           target="_blank"
                           class="mt-2 text-blue-600 hover:underline block">
                            {{ $user->website }}
                        </a>
                    @endif
                </div>
            </div>
        </div>

        <!-- User Posts -->
        <div class="space-y-4">
            @forelse($posts as $post)
                <div class="bg-white rounded-lg shadow p-4">
                    <div class="flex items-center justify-between mb-3">
                        <div class="flex items-center space-x-3">
                            <img src="{{ $post->user->avatar_url }}"
                                 alt="{{ $post->user->name }}"
                                 class="w-10 h-10 rounded-full">
                            <div>
                                <p class="font-semibold">{{ $post->user->name }}</p>
                                <p class="text-sm text-gray-500">{{ $post->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                    </div>

                    <p class="text-gray-800 mb-3">{{ $post->content }}</p>

                    @if($post->image)
                        <img src="{{ $post->image_url }}"
                             alt="Post image"
                             class="w-full rounded-lg">
                    @endif

                    <div class="mt-3 text-sm text-gray-600">
                        {{ $post->likes_count }} likes · {{ $post->comments_count }} comments
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-lg shadow p-8 text-center text-gray-500">
                    No posts yet
                </div>
            @endforelse
        </div>

        <div class="mt-6">
            {{ $posts->links() }}
        </div>
    </div>

    @push('scripts')
        <script>
            function toggleFollow(userId) {
                const btn = document.getElementById(`follow-btn-${userId}`);
                const isFollowing = btn.textContent.trim() === 'Unfollow';
                const url = `/users/${userId}/follow`;
                const method = isFollowing ? 'DELETE' : 'POST';

                fetch(url, {
                    method: method,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    }
                })
                    .then(res => res.json())
                    .then(data => {
                        location.reload();
                    });
            }
        </script>
    @endpush
@endsection
