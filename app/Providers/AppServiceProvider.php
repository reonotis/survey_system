<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // すべてのビューでis_client_domainを共有（ミドルウェア実行後に値を取得）
        view()->composer('*', function ($view) {
            $view->with('is_client_domain', config('app.is_client_domain'));
        });
    }
}
