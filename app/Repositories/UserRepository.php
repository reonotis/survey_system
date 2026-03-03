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
     * @param User $user
     * @param array $param
     * @return bool
     */
    public function update(User $user, array $param): bool
    {
        return $user->update($param);
    }
}
