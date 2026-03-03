<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\User;

class UserRepository
{

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
