<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\User;
use App\Consts\PlanConst;
use Illuminate\Support\Facades\Auth;

/**
 *
 */
class UserController extends Controller
{
    protected User $my_user;
    protected string $my_plan = PlanConst::FREE_PLAN;

    //
    public function __construct()
    {
        parent::__construct();

        $this->my_user = Auth::guard('user')->user();

        // サブスクのプランを判定
        $subscription = $this->getSubscription();
        $stripe_price = $subscription?->stripe_price;

        $this->my_plan = match($stripe_price) {
            'price_1T6CBR4jtTtelMpe0LtkEhP4' => PlanConst::LITE_PLAN,
            'price_1T6CCV4jtTtelMpeblzVIlzH' => PlanConst::FULL_PLAN,
            default => PlanConst::FREE_PLAN,
        };
    }

    public function getSubscription()
    {
        $subscription = $this->my_user
            ->subscriptions()
            ->where('stripe_status', 'active')
            ->first();

        return $subscription;
    }

}
