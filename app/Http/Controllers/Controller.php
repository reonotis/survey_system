<?php

declare(strict_types=1);

namespace App\Http\Controllers;

/**
 *
 */
abstract class Controller
{
    protected bool $is_client_domain = false;

    //
    public function __construct()
    {
        $this->is_client_domain = config('app.is_client_domain');
    }
}
