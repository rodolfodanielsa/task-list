<?php

namespace App\Repositories;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use stdClass;

class UserRepository
{
    public function getUserRole(int $userId): ?stdClass
    {
        return DB::selectOne("SELECT role_name FROM roles r INNER JOIN users u ON u.role_id = r.id WHERE u.id = ?", [$userId]);
    }

    public function getUser(int $userId): ?stdClass
    {
        return DB::selectOne("SELECT * FROM users WHERE id = ?", [$userId]);
    }

    public function getAllUsers(): array
    {
        return DB::select("SELECT * FROM users_copy");
    }
}
