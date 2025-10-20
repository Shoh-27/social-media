@extends('layouts.app')

@section('content')
    <div class="max-w-2xl mx-auto px-4">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 mb-6">
            <h1 class="text-3xl font-bold dark:text-white">#{{ $hashtag->name }}</h1>
            <p class="text-gray-600 dark:text-gray-400 mt-2">{{ $hashtag->posts_count }} posts</p>
        </div>

        @foreach($posts as $post)
            {{-- Include post card here --}}
        @endforeach

        {{ $posts->links() }}
    </div>
@endsection
