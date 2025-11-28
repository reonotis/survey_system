<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Service\BrandService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class NotClientMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (BrandService::isClientDomain()) {
            abort(404);
        }

        return $next($request);
    }
}

