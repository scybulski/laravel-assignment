<?php

namespace App\Adapters\Auth;

use App\Adapters\Auth\Contracts\AuthAdapter;
use App\Enums\SystemsEnum;
use External\Bar\Auth\LoginService as ExternalLoginService;

class BarAuthAdapter implements AuthAdapter
{
    public function __construct(
        protected ExternalLoginService $externalLoginService,
    ) {
    }

    public function authenticate(string $login, string $password): bool
    {
        return $this->externalLoginService->login($login, $password);
    }

    public static function getServiceName(): SystemsEnum
    {
        return SystemsEnum::Bar;
    }
}
