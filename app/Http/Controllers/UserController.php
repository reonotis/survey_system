<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\User;
use App\Consts\PlanConst;
use App\Service\PlanService;
use Illuminate\Support\Facades\Auth;

/**
 *
 */
class UserController extends Controller
{
    protected User $my_user;
    protected string $my_plan = PlanConst::FREE_PLAN;

    /** @var PlanService $plan_service */
    protected PlanService $plan_service;

    public function __construct()
    {
        parent::__construct();

        $this->my_user = Auth::guard('user')->user();
        $this->plan_service = app(PlanService::class);

        // サブスクのプランを判定
        $this->my_plan = $this->plan_service->getPlanByUser($this->my_user->id);
    }

    public function getSubscription()
    {
        return $this->plan_service->getSubscription($this->my_user);
    }

}
