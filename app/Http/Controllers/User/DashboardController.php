<?php

declare(strict_types=1);

namespace App\Http\Controllers\User;

use App\Http\Controllers\UserController;
use Illuminate\Http\Request;

/**
 *
 */
class DashboardController extends UserController
{

    /**
     * コンストラクタ
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     */
    public function __invoke(Request $request)
    {
        return view('user.dashboard');
    }

}
