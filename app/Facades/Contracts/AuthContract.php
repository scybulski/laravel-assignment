<?php

namespace App\Facades\Contracts;

use App\Dtos\AuthDto;

interface AuthContract
{
    /**
     * Authenticates user and returns a JWT token on success.
     *
     * @throws \Illuminate\Auth\AuthenticationException
     */
    public function authenticate(string $login, string $password): AuthDto;
}
