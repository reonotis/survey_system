<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

/**
 *
 */
class UserController extends Controller
{
    protected User|null $my_user = null;

    //
    public function __construct()
    {
        parent::__construct();

        $this->my_user = Auth::guard('user')->user();
    }
}
