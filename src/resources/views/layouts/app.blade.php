<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Social Media') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100">
<!-- Navigation -->
<nav class="bg-white shadow-sm border-b sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center space-x-8">
                <!-- Logo -->
                <a href="{{ route('posts.index') }}" class="text-2xl font-bold text-blue-600">
                    Social
                </a>

                <!-- Search -->
                <form action="{{ route('search.index') }}" method="GET" class="hidden md:block">
                    <input type="text"
                           name="q"
                           placeholder="Search..."
                           class="px-4 py-2 border rounded-full w-64 focus:outline-none focus:ring-2 focus:ring-blue-500"
                           value="{{ request('q') }}">
                </form>
            </div>

            <div class="flex items-center space-x-6">
                <!-- Feed -->
                <a href="{{ route('posts.index') }}" class="text-gray-700 hover:text-blue-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                </a>

                <!-- Dark Mode Toggle -->
                <button onclick="toggleDarkMode()"
                        class="text-gray-700 dark:text-gray-300 hover:text-blue-600">
                    <svg class="w-6 h-6 hidden dark:block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                    <svg class="w-6 h-6 block dark:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                    </svg>
                </button>

                <!-- Messages -->
                <a href="{{ route('messages.index') }}" class="text-gray-700 hover:text-blue-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                    </svg>
                </a>

                <!-- Notifications -->
                <div class="relative">
                    <a href="{{ route('notifications.index') }}" class="text-gray-700 hover:text-blue-600 relative">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                        <span class="notification-badge absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center hidden">
                                0
                            </span>
                    </a>
                </div>

                <!-- Profile -->
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" class="flex items-center space-x-2">
                        <img src="{{ auth()->user()->avatar_url }}"
                             alt="{{ auth()->user()->name }}"
                             class="w-8 h-8 rounded-full">
                    </button>

                    <div x-show="open"
                         @click.away="open = false"
                         class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50">
                        <a href="{{ route('profile.show', auth()->user()->username) }}"
                           class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            My Profile
                        </a>
                        <a href="{{ route('profile.edit') }}"
                           class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            Settings
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                    class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>

<!-- Main Content -->
<main class="py-6">
    @if(session('success'))
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-4">
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                {{ session('success') }}
            </div>
        </div>
    @endif

    @yield('content')
</main>

<!-- Alpine.js for dropdowns -->
<script src="//unpkg.com/alpinejs" defer></script>

<!-- Notification Badge Script -->
<script>
    // Fetch unread notification count
    setInterval(() => {
        fetch('{{ route("notifications.unread") }}')
            .then(res => res.json())
            .then(data => {
                const badge = document.querySelector('.notification-badge');
                if (data.count > 0) {
                    badge.textContent = data.count;
                    badge.classList.remove('hidden');
                } else {
                    badge.classList.add('hidden');
                }
            });
    }, 10000); // Har 10 sekundda
</script>

@stack('scripts')
</body>
</html>

@push('scripts')
    <script type="module">
        const userId = {{ auth()->id() }};

        // Listen for notifications
        Echo.private(`notifications.${userId}`)
            .listen('.notification.created', (e) => {
                // Update badge
                const badge = document.querySelector('.notification-badge');
                const currentCount = parseInt(badge.textContent) || 0;
                badge.textContent = currentCount + 1;
                badge.classList.remove('hidden');

                // Show toast notification
                showNotification(e);

                // Play sound
                new Audio('/sounds/notification.mp3').play().catch(() => {});
            });

        function showNotification(data) {
            const toast = document.createElement('div');
            toast.className = 'fixed top-20 right-4 bg-white shadow-lg rounded-lg p-4 max-w-sm z-50 animate-slide-in';
            toast.innerHTML = `
        <div class="flex items-start space-x-3">
            <img src="${data.sender.avatar}" class="w-10 h-10 rounded-full">
            <div class="flex-1">
                <p class="font-semibold">${data.sender.name}</p>
                <p class="text-sm text-gray-600">${data.message}</p>
            </div>
            <button onclick="this.parentElement.parentElement.remove()"
                    class="text-gray-400 hover:text-gray-600">×</button>
        </div>
    `;

            document.body.appendChild(toast);

            setTimeout(() => toast.remove(), 5000);
        }
    </script>
@endpush


