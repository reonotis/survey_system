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
    private PlanService $plan_service;

    public function __construct()
    {
        parent::__construct();

        $this->my_user = Auth::guard('user')->user();
        $this->plan_service = app(PlanService::class);

        // サブスクのプランを判定
        $subscription = $this->plan_service->getSubscription($this->my_user);
        $stripe_price = $subscription?->stripe_price;
        $this->my_plan = $this->plan_service->getPlan((string)$stripe_price);
    }

    public function getSubscription()
    {
        return $this->plan_service->getSubscription($this->my_user);
    }

}
