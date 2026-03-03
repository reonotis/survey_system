<?php

declare(strict_types=1);

namespace App\Service;

use App\Models\User;
use App\Repositories\UserRepository;

class UserService
{

    private UserRepository $user_repository;

    public function __construct(UserRepository $user_repository)
    {
        $this->user_repository = $user_repository;
    }

    /**
     * @param User $user
     * @param array $param
     * @return bool
     */
    public function update(User $user, array $param): bool
    {
        return $this->user_repository->update($user, $param);
    }
}
