@extends('layouts.app')

@section('content')
    <div class="max-w-2xl mx-auto px-4">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
            <div class="p-4 border-b dark:border-gray-700">
                <h2 class="text-xl font-bold dark:text-white">Notifications</h2>
            </div>

            <div class="divide-y dark:divide-gray-700">
                @forelse($notifications as $notification)
                    <div class="p-4 hover:bg-gray-50 dark:hover:bg-gray-700 {{ $notification->is_read ? 'opacity-60' : 'bg-blue-50 dark:bg-blue-900/20' }}">
                        <div class="flex items-start space-x-3">
                            <img src="{{ $notification->sender->avatar_url }}"
                                 alt="{{ $notification->sender->name }}"
                                 class="w-10 h-10 rounded-full">

                            <div class="flex-1">
                                <p class="text-sm dark:text-gray-200">
                                    <a href="{{ route('profile.show', $notification->sender->username) }}"
                                       class="font-semibold hover:underline">
                                        {{ $notification->sender->name }}
                                    </a>
                                    {{ $notification->message }}
                                </p>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                    {{ $notification->created_at->diffForHumans() }}
                                </p>
                            </div>

                            <!-- Notification Icon -->
                            @if($notification->type === 'like')
                                <svg class="w-5 h-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"/>
                                </svg>
                            @elseif($notification->type === 'comment')
                                <svg class="w-5 h-5 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10c0 3.866-3.582 7-8 7a8.841 8.841 0 01-4.083-.98L2 17l1.338-3.123C2.493 12.767 2 11.434 2 10c0-3.866 3.582-7 8-7s8 3.134 8 7zM7 9H5v2h2V9zm8 0h-2v2h2V9zM9 9h2v2H9V9z" clip-rule="evenodd"/>
                                </svg>
                            @else
                                <svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M8 9a3 3 0 100-6 3 3 0 000 6zM8 11a6 6 0 016 6H2a6 6 0 016-6zM16 7a1 1 0 10-2 0v1h-1a1 1 0 100 2h1v1a1 1 0 102 0v-1h1a1 1 0 100-2h-1V7z"/>
                                </svg>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="p-8 text-center text-gray-500 dark:text-gray-400">
                        No notifications yet
                    </div>
                @endforelse
            </div>
        </div>

        <div class="mt-6">
            {{ $notifications->links() }}
        </div>
    </div>
@endsection
