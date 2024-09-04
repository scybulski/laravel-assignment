<?php

namespace App\Facades;

use App\Facades\Contracts\AuthContract;
use App\Dtos\AuthDto;
use App\Enums\SystemsEnum;
use App\Factories\Contracts\AuthAdapterFactoryContract;
use Firebase\JWT\JWT;
use Illuminate\Auth\AuthenticationException;

class AuthFacade implements AuthContract
{
    public function __construct(
        protected AuthAdapterFactoryContract $authAdapterFactory,
    ) {
    }

    /**
     * @inheritDoc
     */
    public function authenticate(string $login, string $password): AuthDto
    {
        $authAdapter = $this->authAdapterFactory->create($login);

        return $authAdapter?->authenticate($login, $password)
            ? new AuthDto(token: $this->generateJwtToken($login, $authAdapter::getServiceName()))
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
