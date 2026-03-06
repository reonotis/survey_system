<?php

declare(strict_types=1);

namespace App\Service;

use App\Models\User;
use App\Repositories\UserRepository;

class UserService
{
    private UserRepository $user_repository;

    /**
     * コンストラクタ
     */
    public function __construct(UserRepository $user_repository)
    {
        $this->user_repository = $user_repository;
    }

    /**
     * @param string $email
     * @return User|null
     */
    public function getByEmail(string $email): ?User
    {
        return $this->user_repository->getByEmail($email);
    }

    /**
     * @param array $param
     * @return User|null
     */
    public function create(array $param): ?User
    {
        $param['host'] = request()->getHost();

        return $this->user_repository->create($param);
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
