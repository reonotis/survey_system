<?php

declare(strict_types=1);

namespace App\Service;

use App\Consts\PlanConst;
use App\Models\User;
use App\Repositories\UserRepository;

class PlanService
{
    private UserRepository $user_repository;

    public function __construct(UserRepository $user_repository)
    {
        $this->user_repository = $user_repository;
    }

    /**
     * @param User $user
     * @return null
     */
    public function getSubscription(User $user)
    {
        return $user
            ->subscriptions()
            ->where('stripe_status', 'active')
            ->first();
    }

    /**
     * 対象ユーザーのプランを取得する
     * @param int $owner_id
     * @return string
     */
    public function getPlanByUser(int $owner_id): string
    {
        $owner = $this->user_repository->getById($owner_id);
        $subscription = $this->getSubscription($owner);
        $stripe_price = $subscription?->stripe_price;

        return $this->getPlan((string)$stripe_price);
    }

    /**
     * @param string $stripe_price
     * @return string
     */
    public function getPlan(string $stripe_price): string
    {
        return match ($stripe_price) {
            config('services.stripe.plans.lite') => PlanConst::LITE_PLAN,
            config('services.stripe.plans.full') => PlanConst::FULL_PLAN,
            default => PlanConst::FREE_PLAN,
        };
    }

}
