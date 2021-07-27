<?php

namespace App\Strategies;

use Illuminate\Support\Collection;

interface TaskStrategy
{
    public function getTasks(int $userId): array;
}
