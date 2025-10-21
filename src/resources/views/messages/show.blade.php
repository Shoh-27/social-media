@extends('layouts.app')

@section('content')
    <div class="max-w-4xl mx-auto px-4">
        <div class="bg-white rounded-lg shadow overflow-hidden flex flex-col" style="height: calc(100vh - 150px);">

            <!-- Chat Header -->
            <div class="p-4 border-b flex items-center justify-between bg-gray-50">
                <div class="flex items-center space-x-3">
                    <a href="{{ route('messages.index') }}"
                       class="text-gray-600 hover:text-gray-900 mr-2">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                    </a>

                    <img src="{{ $user->avatar_url }}"
                         alt="{{ $user->name }}"
                         class="w-10 h-10 rounded-full">

                    <div>
                        <a href="{{ route('profile.show', $user->username) }}"
                           class="font-semibold hover:underline">
                            {{ $user->name }}
                        </a>
                        <p class="text-sm text-gray-500">@{{ $user->username }}</p>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex items-center space-x-2">
                    <button class="p-2 text-gray-600 hover:bg-gray-100 rounded-full">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                        </svg>
                    </button>
                    <button class="p-2 text-gray-600 hover:bg-gray-100 rounded-full">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Messages Container -->
            <div id="messages-container"
                 class="flex-1 overflow-y-auto p-4 space-y-4 bg-gray-50"
                 style="scroll-behavior: smooth;">

                @forelse($messages as $message)
                    <div class="flex {{ $message->sender_id === auth()->id() ? 'justify-end' : 'justify-start' }}">
                        <div class="flex items-end space-x-2 max-w-md">
                            @if($message->sender_id !== auth()->id())
                                <img src="{{ $message->sender->avatar_url }}"
                                     alt="{{ $message->sender->name }}"
                                     class="w-8 h-8 rounded-full">
                            @endif

                            <div class="flex flex-col {{ $message->sender_id === auth()->id() ? 'items-end' : 'items-start' }}">
                                <div class="px-4 py-2 rounded-2xl {{ $message->sender_id === auth()->id() ? 'bg-blue-600 text-white' : 'bg-white text-gray-800' }} shadow">
                                    <p class="break-words">{{ $message->message }}</p>
                                </div>
                                <span class="text-xs text-gray-500 mt-1 px-2">
                                {{ $message->created_at->format('H:i') }}
                            </span>
                            </div>

                            @if($message->sender_id === auth()->id())
                                <img src="{{ $message->sender->avatar_url }}"
                                     alt="{{ $message->sender->name }}"
                                     class="w-8 h-8 rounded-full">
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="text-center py-12 text-gray-500">
                        <svg class="w-12 h-12 mx-auto mb-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                        </svg>
                        <p>No messages yet. Say hi! 👋</p>
                    </div>
                @endforelse
            </div>

            <!-- Message Input -->
            <div class="p-4 border-t bg-white">
                <form id="message-form" class="flex items-end space-x-3">
                    @csrf

                    <!-- Emoji Button (optional) -->
                    <button type="button"
                            class="p-2 text-gray-600 hover:bg-gray-100 rounded-full">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </button>

                    <!-- Image Upload (optional) -->
                    <button type="button"
                            class="p-2 text-gray-600 hover:bg-gray-100 rounded-full">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </button>

                    <!-- Message Input -->
                    <div class="flex-1 relative">
                    <textarea
                        id="message-input"
                        rows="1"
                        placeholder="Type a message..."
                        class="w-full px-4 py-3 border rounded-full focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none"
                        style="max-height: 120px;"
                        onkeydown="if(event.key === 'Enter' && !event.shiftKey) { event.preventDefault(); document.getElementById('send-btn').click(); }"></textarea>
                    </div>

                    <!-- Send Button -->
                    <button
                        id="send-btn"
                        type="submit"
                        class="p-3 bg-blue-600 text-white rounded-full hover:bg-blue-700 transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                        </svg>
                    </button>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script type="module">
            const userId = {{ $user->id }};
            const currentUserId = {{ auth()->id() }};

            // Scroll to bottom on load
            const container = document.getElementById('messages-container');
            container.scrollTop = container.scrollHeight;

            // Listen for new messages (Real-time)
            Echo.private(`chat.${currentUserId}`)
                .listen('.message.sent', (e) => {
                    if (e.sender.id === userId) {
                        appendMessage(e, false);
                    }
                });

            // Send message
            document.getElementById('message-form').addEventListener('submit', async (e) => {
                e.preventDefault();

                const messageInput = document.getElementById('message-input');
                const message = messageInput.value.trim();

                if (!message) return;

                try {
                    const response = await fetch(`/messages/${userId}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        },
                        body: JSON.stringify({ message })
                    });

                    const data = await response.json();

                    if (data.success) {
                        appendMessage({
                            sender: {
                                id: currentUserId,
                                name: '{{ auth()->user()->name }}',
                                avatar: '{{ auth()->user()->avatar_url }}'
                            },
                            message: message,
                            created_at: new Date().toISOString()
                        }, true);

                        messageInput.value = '';
                        autoResize(messageInput);
                    }
                } catch (error) {
                    console.error('Error:', error);
                }
            });

            // Auto-resize textarea
            const textarea = document.getElementById('message-input');
            textarea.addEventListener('input', function() {
                autoResize(this);
            });

            function autoResize(textarea) {
                textarea.style.height = 'auto';
                textarea.style.height = Math.min(textarea.scrollHeight, 120) + 'px';
            }

            // Append message to chat
            function appendMessage(data, isSent) {
                const isOwn = isSent || data.sender.id === currentUserId;
                const time = new Date(data.created_at || Date.now()).toLocaleTimeString('en-US', {
                    hour: '2-digit',
                    minute: '2-digit'
                });

                const messageHtml = `
        <div class="flex ${isOwn ? 'justify-end' : 'justify-start'}">
            <div class="flex items-end space-x-2 max-w-md">
                ${!isOwn ? `<img src="${data.sender.avatar}" alt="${data.sender.name}" class="w-8 h-8 rounded-full">` : ''}

                <div class="flex flex-col ${isOwn ? 'items-end' : 'items-start'}">
                    <div class="px-4 py-2 rounded-2xl ${isOwn ? 'bg-blue-600 text-white' : 'bg-white text-gray-800'} shadow">
                        <p class="break-words">${data.message}</p>
                    </div>
                    <span class="text-xs text-gray-500 mt-1 px-2">${time}</span>
                </div>

                ${isOwn ? `<img src="${data.sender.avatar}" alt="${data.sender.name}" class="w-8 h-8 rounded-full">` : ''}
            </div>
        </div>
    `;

                container.insertAdjacentHTML('beforeend', messageHtml);
                container.scrollTop = container.scrollHeight;

                // Play notification sound (optional)
                if (!isOwn) {
                    new Audio('/sounds/notification.mp3').play().catch(() => {});
                }
            }
        </script>
    @endpush
@endsection
