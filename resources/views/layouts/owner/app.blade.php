<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>管理者ダッシュボード - アンケートシステム</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />


        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/owner/app.js'])

        <!-- Page-specific scripts -->
        @stack('scripts')

    </head>

    <body class="font-sans antialiased">
        <div class="min-h-screen">

            <header >
                <div class="custom-container">
                    <div class="flex-between-center py-2">
                        <div>
                            <a href="#" class="text-white text-xl font-semibold">アンケートシステム - 管理者</a>
                        </div>
                        <div>
                            <form method="POST" action="{{ route('owner_logout') }}" class="inline">
                                @csrf
                                <button type="submit" class="bg-transparent hover:bg-gray-700 text-white font-semibold py-2 px-4 border border-white rounded transition duration-150 ease-in-out">
                                    ログアウト
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </header>

            @isset($breadcrumbs)
                <div class="breadcrumbs">
                    {{ $breadcrumbs }}
                </div>
            @endisset

            @isset($page_name)
                <div class="page-name">
                    <div class="custom-container">
                        <p class="">{{ $page_name }}</p>
                    </div>
                </div>
            @endisset

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>
    </body>
</html>
