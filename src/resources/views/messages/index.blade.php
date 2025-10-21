@extends('layouts.app')

@section('content')
    <div class="max-w-6xl mx-auto px-4">
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <!-- Header -->
            <div class="p-4 border-b">
                <h2 class="text-xl font-bold">Messages</h2>
            </div>

            @if($conversations->count() > 0)
                <!-- Conversations List -->
                <div class="divide-y">
                    @foreach($conversations as $message)
                        @php
                            $otherUser = $message->sender_id === auth()->id()
                                ? $message->receiver
                                : $message->sender;
                        @endphp

                        <a href="{{ route('messages.show', $otherUser) }}"
                           class="flex items-center p-4 hover:bg-gray-50 transition">
                            <!-- Avatar -->
                            <div class="relative">
                                <img src="{{ $otherUser->avatar_url }}"
                                     alt="{{ $otherUser->name }}"
                                     class="w-14 h-14 rounded-full">

                                <!-- Online Status (optional) -->
                                <span class="absolute bottom-0 right-0 w-4 h-4 bg-green-500 border-2 border-white rounded-full"></span>
                            </div>

                            <!-- Message Info -->
                            <div class="ml-4 flex-1 min-w-0">
                                <div class="flex items-center justify-between">
                                    <h3 class="font-semibold text-gray-900 truncate">
                                        {{ $otherUser->name }}
                                    </h3>
                                    <span class="text-xs text-gray-500">
                                    {{ $message->created_at->diffForHumans() }}
                                </span>
                                </div>

                                <p class="text-sm text-gray-600 truncate mt-1">
                                    @if($message->sender_id === auth()->id())
                                        <span class="text-gray-500">You:</span>
                                    @endif
                                    {{ Str::limit($message->message, 50) }}
                                </p>
                            </div>

                            <!-- Unread Badge -->
                            @php
                                $unreadCount = \App\Models\Message::where('sender_id', $otherUser->id)
                                    ->where('receiver_id', auth()->id())
                                    ->where('is_read', false)
                                    ->count();
                            @endphp

                            @if($unreadCount > 0)
                                <div class="ml-2">
                                <span class="bg-blue-600 text-white text-xs font-bold px-2 py-1 rounded-full">
                                    {{ $unreadCount }}
                                </span>
                                </div>
                            @endif
                        </a>
                    @endforeach
                </div>
            @else
                <!-- Empty State -->
                <div class="p-12 text-center">
                    <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                    </svg>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">No messages yet</h3>
                    <p class="text-gray-600">Start a conversation with someone!</p>
                </div>
            @endif
        </div>
    </div>
@endsection
