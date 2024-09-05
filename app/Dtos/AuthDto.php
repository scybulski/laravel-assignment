<?php

namespace App\Dtos;

class AuthDto
{
    public function __construct(
        public readonly ?string $token,
    ) {
    }
}
