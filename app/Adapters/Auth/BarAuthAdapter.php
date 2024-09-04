<?php

namespace App\Adapters\Auth;

use App\Adapters\Auth\Contracts\AuthAdapter;
use App\Enums\SystemsEnum;

class BarAuthAdapter implements AuthAdapter
{
    public function authenticate(string $login, string $password): bool
    {
        return false;
    }

    public static function getServiceName(): SystemsEnum
    {
        return SystemsEnum::Bar;
    }
}
