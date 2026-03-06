<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\User;

class UserRepository
{

    public function getById(int $id): ?User
    {
        return User::find($id);
    }

    /**
     * @param string $email
     * @return User|null
     */
    public function getByEmail(string $email): ?User
    {
        return User::where('email', $email)->first();
    }

    /**
     * @param array $param
     * @return User
     */
    public function create(array $param): User
    {
        return User::create($param);
    }

    /**
     * @param User $user
     * @param array $param
     * @return bool
     */
    public function update(User $user, array $param): bool
    {
        return $user->update($param);
    }
}
