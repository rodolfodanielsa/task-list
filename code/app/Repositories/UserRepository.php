<?php

namespace App\Repositories;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class UserRepository
{
    protected User $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function getUserRole(int $userId): Role
    {
        return $this->getUser($userId);
    }

    protected function getUser(int $userId): Role
    {
        return $this->user->find($userId)->role;
    }

    public function getAllUsers(): Collection
    {
        return $this->user->all();
    }
}
