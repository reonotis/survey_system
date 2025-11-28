<?php

declare(strict_types=1);

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: [
            __DIR__.'/../routes/admin_api.php',
            __DIR__.'/../routes/owner_api.php',
        ],
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // ミドルウェアの登録
        $middleware->alias([
            'auth.custom' => \App\Http\Middleware\RedirectIfNotAuthenticated::class,
            'auth.api' => \App\Http\Middleware\ApiAuthenticate::class,
            'client_only' => \App\Http\Middleware\ClientOnlyMiddleware::class,
            'not_client' => \App\Http\Middleware\NotClientMiddleware::class,
        ]);

        // APIルートにもセッションベースの認証を可能にするため、必要なミドルウェアを追加
        // CookieEncryptionとStartSessionを追加（認証情報をセッションから読み取るため）
        $middleware->appendToGroup('api', [
            \Illuminate\Cookie\Middleware\EncryptCookies::class,
            \Illuminate\Session\Middleware\StartSession::class,
        ]);

        // 全リクエストに適用
        $middleware->append(\App\Http\Middleware\BrandMiddleware::class);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
