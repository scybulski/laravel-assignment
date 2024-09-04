<?php

namespace App\Facades\Contracts;

interface AuthContract
{
    /**
     * Authenticates user and returns a JWT token on success.
     *
     * @throws \Illuminate\Auth\AuthenticationException
     */
    public function authenticate(string $login, string $password): string;
}
