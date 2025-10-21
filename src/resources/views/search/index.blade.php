@extends('layouts.app')

@section('content')
    <div class="max-w-4xl mx-auto px-4">
        <!-- Search Bar -->
        <div class="bg-white rounded-lg shadow p-4 mb-6">
            <form action="{{ route('search.index') }}" method="GET">
                <div class="relative">
                    <input
                        type="text"
                        name="q"
                        value="{{ $query }}"
                        placeholder="Search for users and posts..."
                        class="w-full px-4 py-3 pl-12 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        autofocus>

                    <svg class="absolute left-4 top-3.5 w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
            </form>
        </div>

        @if($query)
            <!-- Tabs -->
            <div class="bg-white rounded-lg shadow mb-6" x-data="{ tab: 'users' }">
                <div class="flex border-b">
                    <button @click="tab = 'users'"
                            :class="tab === 'users' ? 'border-b-2 border-blue-600 text-blue-600' : 'text-gray-600'"
                            class="px-6 py-3 font-semibold focus:outline-none">
                        Users ({{ $users->count() }})
                    </button>
                    <button @click="tab = 'posts'"
                            :class="tab === 'posts' ? 'border-b-2 border-blue-600 text-blue-600' : 'text-gray-600'"
                            class="px-6 py-3 font-semibold focus:outline-none">
                        Posts ({{ $posts->count() }})
                    </button>
                </div>

                <!-- Users Tab -->
                <div x-show="tab === 'users'" class="divide-y">
                    @forelse($users as $user)
                        <div class="p-4 hover:bg-gray-50 flex items-center justify-between">
                            <div class="flex items-center space-x-4">
                                <a href="{{ route('profile.show', $user->username) }}">
                                    <img src="{{ $user->avatar_url }}"
                                         alt="{{ $user->name }}"
                                         class="w-14 h-14 rounded-full">
                                </a>

                                <div>
                                    <a href="{{ route('profile.show', $user->username) }}"
                                       class="font-bold text-lg hover:underline">
                                        {{ $user->name }}
                                    </a>
                                    <p class="text-gray-600">@{{ $user->username }}</p>

                                    @if($user->bio)
                                        <p class="text-sm text-gray-700 mt-1">
                                            {{ Str::limit($user->bio, 80) }}
                                        </p>
                                    @endif

                                    <div class="flex items-center space-x-3 mt-2 text-sm text-gray-500">
                                        <span>{{ $user->followers->count() }} followers</span>
                                        <span>{{ $user->posts->count() }} posts</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Follow Button -->
                            @if(auth()->id() !== $user->id)
                                <button
                                    onclick="toggleFollow({{ $user->id }})"
                                    id="follow-btn-{{ $user->id }}"
                                    class="px-6 py-2 rounded-lg font-semibold transition
                                    {{ auth()->user()->isFollowing($user)
                                        ? 'bg-gray-200 text-gray-800 hover:bg-gray-300'
                                        : 'bg-blue-600 text-white hover:bg-blue-700' }}">
                                    {{ auth()->user()->isFollowing($user) ? 'Unfollow' : 'Follow' }}
                                </button>
                            @endif
                        </div>
                    @empty
                        <div class="p-12 text-center text-gray-500">
                            <svg class="w-16 h-16 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                            <p class="text-lg font-semibold mb-1">No users found</p>
                            <p>Try searching with different keywords</p>
                        </div>
                    @endforelse
                </div>

                <!-- Posts Tab -->
                <div x-show="tab === 'posts'" class="divide-y">
                    @forelse($posts as $post)
                        <div class="p-4 hover:bg-gray-50">
                            <!-- Post Header -->
                            <div class="flex items-center space-x-3 mb-3">
                                <a href="{{ route('profile.show', $post->user->username) }}">
                                    <img src="{{ $post->user->avatar_url }}"
                                         alt="{{ $post->user->name }}"
                                         class="w-10 h-10 rounded-full">
                                </a>
                                <div>
                                    <a href="{{ route('profile.show', $post->user->username) }}"
                                       class="font-semibold hover:underline">
                                        {{ $post->user->name }}
                                    </a>
                                    <p class="text-sm text-gray-500">
                                        {{ $post->created_at->diffForHumans() }}
                                    </p>
                                </div>
                            </div>

                            <!-- Post Content -->
                            <p class="text-gray-800 mb-3 whitespace-pre-wrap">
                                {!! preg_replace('/#(\w+)/', '<a href="/hashtag/$1" class="text-blue-600 hover:underline">#$1</a>', e($post->content)) !!}
                            </p>

                            <!-- Post Image -->
                            @if($post->image)
                                <img src="{{ $post->image_url }}"
                                     alt="Post image"
                                     class="w-full rounded-lg mb-3">
                            @endif

                            <!-- Post Stats -->
                            <div class="flex items-center space-x-4 text-sm text-gray-600">
                                <span>❤️ {{ $post->likes_count }} likes</span>
                                <span>💬 {{ $post->comments_count }} comments</span>
                                <span>👁️ {{ $post->views_count }} views</span>
                            </div>
                        </div>
                    @empty
                        <div class="p-12 text-center text-gray-500">
                            <svg class="w-16 h-16 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <p class="text-lg font-semibold mb-1">No posts found</p>
                            <p>Try searching with different keywords</p>
                        </div>
                    @endforelse
                </div>
            </div>
        @else
            <!-- Empty State -->
            <div class="bg-white rounded-lg shadow p-12 text-center">
                <svg class="w-20 h-20 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <h3 class="text-2xl font-bold text-gray-900 mb-2">Search Social Media</h3>
                <p class="text-gray-600">Find people, posts, and hashtags</p>
            </div>
        @endif
    </div>

    @push('scripts')
        <script>
            // Follow/Unfollow function
            async function toggleFollow(userId) {
                const btn = document.getElementById(`follow-btn-${userId}`);
                const isFollowing = btn.textContent.trim() === 'Unfollow';
                const url = `/users/${userId}/follow`;
                const method = isFollowing ? 'DELETE' : 'POST';

                try {
                    const response = await fetch(url, {
                        method: method,
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json',
                        }
                    });

                    if (response.ok) {
                        if (isFollowing) {
                            btn.textContent = 'Follow';
                            btn.className = 'px-6 py-2 rounded-lg font-semibold transition bg-blue-600 text-white hover:bg-blue-700';
                        } else {
                            btn.textContent = 'Unfollow';
                            btn.className = 'px-6 py-2 rounded-lg font-semibold transition bg-gray-200 text-gray-800 hover:bg-gray-300';
                        }
                    }
                } catch (error) {
                    console.error('Error:', error);
                }
            }
        </script>
    @endpush
@endsection
