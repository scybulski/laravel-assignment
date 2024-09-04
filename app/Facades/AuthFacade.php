<?php

namespace App\Facades;

use App\Facades\Contracts\AuthContract;
use App\Adapters\Auth\BarAuthAdapter;
use App\Adapters\Auth\BazAuthAdapter;
use App\Adapters\Auth\FooAuthAdapter;
use App\Enums\SystemsEnum;
use Firebase\JWT\JWT;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Str;

class AuthFacade implements AuthContract
{
    /**
     * @inheritDoc
     */
    public function authenticate(string $login, string $password): string
    {
        /** @var \App\Adapters\Auth\Contracts\AuthAdapter|null $authAdapter */
        $authAdapter = match (true) {
            Str::startsWith($login, 'FOO_') => app(FooAuthAdapter::class),
            Str::startsWith($login, 'BAR_') => app(BarAuthAdapter::class),
            Str::startsWith($login, 'BAZ_') => app(BazAuthAdapter::class),
            default => null,
        };

        return $authAdapter?->authenticate($login, $password)
            ? $this->generateJwtToken($login, $authAdapter::getServiceName())
            : throw new AuthenticationException();
    }

    protected function generateJwtToken(string $login, SystemsEnum $system): string
    {
        $key = config('app.jwt.secret');

        $payload = [
            'iss' => parse_url(config('app.url'), PHP_URL_HOST),
            'exp' => time() + config('app.jwt.ttl'),
            'login' => $login,
            'system' => $system->name,
        ];

        return JWT::encode($payload, $key, 'HS256');
    }
}
