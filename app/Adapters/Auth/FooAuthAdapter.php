<?php

namespace App\Adapters\Auth;

use App\Adapters\Auth\Contracts\AuthAdapter;
use App\Enums\SystemsEnum;
use External\Foo\Auth\AuthWS as ExternalAuthWS;
use External\Foo\Exceptions\AuthenticationFailedException as ExternalAuthenticationFailedException;

class FooAuthAdapter implements AuthAdapter
{
    public function __construct(protected ExternalAuthWS $externalAuthWS)
    {
    }

    public function authenticate(string $login, string $password): bool
    {
        try {
            $this->externalAuthWS->authenticate($login, $password);
        } catch (ExternalAuthenticationFailedException) {
            return false;
        }

        return true;
    }

    public static function getServiceName(): SystemsEnum
    {
        return SystemsEnum::Foo;
    }
}
