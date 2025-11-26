<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Service\BrandService;
use Closure;

class BrandMiddleware
{
    public function handle($request, Closure $next)
    {
        config(['app.is_client_domain' => BrandService::isClientDomain()]);

        return $next($request);
    }
}
