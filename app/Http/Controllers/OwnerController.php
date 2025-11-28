<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Owner;
use Illuminate\Support\Facades\Auth;

/**
 *
 */
class OwnerController extends Controller
{
    protected Owner|null $my_owner = null;

    //
    public function __construct()
    {
        parent::__construct();
        $this->my_owner = Auth::guard('owner')->user();
    }
}
