<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>システム管理者ダッシュボード - アンケートシステム</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />


        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/admin/app.js'])

        <!-- Page-specific scripts -->
        @stack('scripts')

    </head>

    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">

            <header >
                <nav class="bg-gray-800 shadow-lg">
                    <div class="mx-auto px-4 sm:px-6 lg:px-8">
                        <div class="flex justify-between h-16">
                            <div class="flex items-center">
                                <a href="#" class="text-white text-xl font-semibold">アンケートシステム - 管理者</a>
                            </div>
                            <div class="flex items-center">
                                <form method="POST" action="{{ route('admin_logout') }}" class="inline">
                                    @csrf
                                    <button type="submit" class="bg-transparent hover:bg-gray-700 text-white font-semibold py-2 px-4 border border-white rounded transition duration-150 ease-in-out">
                                        ログアウト
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </nav>
            </header>

            @isset($page_name)
                <div class="page-name">
                    <p class="text-3xl font-bold text-gray-900">{{ $page_name }}</p>
                </div>
            @endisset

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>
    </body>
</html>
