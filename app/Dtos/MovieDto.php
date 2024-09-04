<?php

namespace App\Dtos;

class MovieDto
{
    public function __construct(
        public readonly string $title,
    ) {
    }
}
