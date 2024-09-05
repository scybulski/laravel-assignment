<?php

namespace App\Dtos;

use InvalidArgumentException;

class MovieCollectionDto
{
    public function __construct(
        public readonly array $movies,
    ) {
        foreach ($this->movies as $movie) {
            if (!($movie instanceof MovieDto)) {
                throw new InvalidArgumentException('Invalid movie type');
            }
        }
    }

    public function merge(MovieCollectionDto $movieCollectionDto): MovieCollectionDto
    {
        return new MovieCollectionDto(
            array_merge($this->movies, $movieCollectionDto->movies),
        );
    }
}
