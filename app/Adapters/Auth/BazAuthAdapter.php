<?php

namespace App\Adapters\Auth;

use App\Adapters\Auth\Contracts\AuthAdapter;
use App\Enums\SystemsEnum;
use External\Baz\Auth\Authenticator as ExternalAuthenticator;
use External\Baz\Auth\Responses\Success as ExternalSuccessResponse;

class BazAuthAdapter implements AuthAdapter
{
    public function __construct(protected ExternalAuthenticator $externalAuthenticator)
    {
    }

    public function authenticate(string $login, string $password): bool
    {
        return $this->externalAuthenticator->auth($login, $password) instanceof ExternalSuccessResponse;
    }

    public static function getServiceName(): SystemsEnum
    {
        return SystemsEnum::Baz;
    }
}
