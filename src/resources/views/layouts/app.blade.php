<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{ darkMode: localStorage.getItem('theme') === 'dark' }"
      x-init="$watch('darkMode', val => localStorage.setItem('theme', val ? 'dark' : 'light'))"
      x-bind:class="{ 'dark': darkMode }">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Social Media') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        @keyframes slide-in {
            from { opacity: 0; transform: translateX(50px); }
            to { opacity: 1; transform: translateX(0); }
        }
        .animate-slide-in {
            animation: slide-in 0.4s ease-out;
        }
        .notification-badge {
            transition: all 0.3s ease;
        }
    </style>
</head>

<body class="bg-gray-100 dark:bg-gray-900 text-gray-800 dark:text-gray-100 transition-colors duration-300">

<!-- 🌐 Navigation -->
<nav class="bg-white dark:bg-gray-800 shadow-md border-b border-gray-200 dark:border-gray-700 sticky top-0 z-50 backdrop-blur-sm bg-opacity-95">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">

            <!-- Left side -->
            <div class="flex items-center space-x-6">
                <!-- Logo -->
                <a href="{{ route('posts.index') }}" class="text-2xl font-bold text-blue-600 dark:text-blue-400 hover:opacity-80 transition">
                    <span class="bg-gradient-to-r from-blue-500 to-purple-600 bg-clip-text text-transparent">
                        Social
                    </span>
                </a>

                <!-- Search -->
                <form action="{{ route('search.index') }}" method="GET" class="hidden md:block relative">
                    <input type="text" name="q"
                           placeholder="🔍 Search..."
                           class="pl-10 pr-4 py-2 border dark:border-gray-700 rounded-full w-72 focus:ring-2 focus:ring-blue-500 focus:outline-none text-sm shadow-sm dark:bg-gray-900 dark:text-gray-200"
                           value="{{ request('q') }}">
                    <svg class="w-5 h-5 text-gray-400 absolute left-3 top-2.5" fill="none" stroke="currentColor"
                         viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 1116.65 16.65z"/>
                    </svg>
                </form>
            </div>

            <!-- Right side -->
            <div class="flex items-center space-x-6">

                <!-- Feed -->
                <a href="{{ route('posts.index') }}" class="text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4h2v4a1 1 0 001 1h2"/>
                    </svg>
                </a>

                <!-- Messages -->
                <a href="{{ route('messages.index') }}" class="relative text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                    </svg>
                </a>

                <!-- Notifications -->
                <div class="relative">
                    <a href="{{ route('notifications.index') }}" class="relative text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1"/>
                        </svg>
                        <span
                            class="notification-badge absolute -top-1 -right-1 bg-red-500 text-white text-xs font-semibold rounded-full w-5 h-5 flex items-center justify-center hidden shadow-md">
                            0
                        </span>
                    </a>
                </div>

                <!-- 🌙 Theme toggle -->
                <button @click="darkMode = !darkMode"
                        class="p-2 rounded-full bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 transition">
                    <template x-if="!darkMode">
                        <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 3v1m0 16v1m8.485-9h1M3 12H2m15.364 6.364l.707.707M6.343 6.343l-.707-.707m12.021 12.021a9 9 0 11-12.728-12.728 9 9 0 0012.728 12.728z"/>
                        </svg>
                    </template>
                    <template x-if="darkMode">
                        <svg class="w-6 h-6 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M17.293 13.293A8 8 0 016.707 2.707a8 8 0 1010.586 10.586z"/>
                        </svg>
                    </template>
                </button>

                <!-- Profile Dropdown -->
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" class="flex items-center space-x-2 focus:outline-none">
                        <img src="{{ auth()->user()->avatar_url }}"
                             alt="{{ auth()->user()->name }}"
                             class="w-9 h-9 rounded-full ring-2 ring-blue-500">
                    </button>

                    <!-- Dropdown -->
                    <div x-show="open"
                         @click.away="open = false"
                         x-transition
                         class="absolute right-0 mt-3 w-48 bg-white dark:bg-gray-800 rounded-lg shadow-lg py-2 z-50 border border-gray-100 dark:border-gray-700">
                        <a href="{{ route('profile.show', auth()->user()->username) }}"
                           class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 transition">👤 My Profile</a>
                        <a href="{{ route('profile.edit') }}"
                           class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 transition">⚙️ Settings</a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                    class="w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                                🚪 Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>

<!-- 🌟 Main Content -->
<main class="py-8">
    @if(session('success'))
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-4">
            <div class="bg-green-100 dark:bg-green-800 border border-green-400 dark:border-green-600 text-green-700 dark:text-green-200 px-4 py-3 rounded-md shadow-sm">
                {{ session('success') }}
            </div>
        </div>
    @endif

    @yield('content')
</main>

<!-- Alpine.js -->
<script src="//unpkg.com/alpinejs" defer></script>

<!-- 🔔 Notification Script -->
<script>
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
    }, 10000);
</script>

@stack('scripts')
</body>
</html>
