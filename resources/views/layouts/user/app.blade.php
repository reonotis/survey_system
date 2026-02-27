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

        <link rel="icon" type="image/png" href="/icon/favicon.svg">


        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/user/app.js'])

        <!-- Page-specific scripts -->
        @stack('scripts')

    </head>

    <body class="font-sans antialiased">
        <div class="min-h-screen" style="position: relative;">

            <header >
                <div class="custom-container">
                    <div class="flex-between-center py-2">
                        <div>
                            <a href="{{ route('user_dashboard') }}" class="text-xl font-semibold">フォームメーカー - 管理者</a>
                        </div>

                        @include('layouts.user.header-navigation')
                        <div>
                            <form method="POST" action="{{ route('user_logout') }}" class="inline">
                                @csrf
                                <button type="submit" class="btn">
                                    ログアウト
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </header>

            @isset($page_name)
                <div class="page-name-navigation">
                    <div class="custom-container">
                        {{ $page_name }}
                    </div>
                </div>
            @endisset

            @include('components.session-message')

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>
    </body>
</html>
