<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Owner;
use Illuminate\Support\Facades\Auth;

/**
 *
 */
abstract class Controller
{
    protected bool $is_client_domain = false;
    protected Owner|null $my_owner = null;

    //
    public function __construct()
    {

        $this->my_owner = Auth::guard('owner')->user();
        $this->is_client_domain = config('app.is_client_domain');
    }
}
