<?php

namespace App\Adapters\Auth\Contracts;

use App\Enums\SystemsEnum;

interface AuthAdapter
{
    public function authenticate(string $login, string $password): bool;

    public static function getServiceName(): SystemsEnum;
}
