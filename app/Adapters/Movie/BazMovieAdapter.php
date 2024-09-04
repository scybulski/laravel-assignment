<?php

namespace App\Adapters\Movie;

use App\Dtos\MovieCollectionDto;
use App\Dtos\MovieDto;
use App\Enums\SystemsEnum;
use External\Baz\Movies\MovieService as BazMovieService;

class BazMovieAdapter extends AbstractMovieAdapter
{
    public function __construct(
        protected BazMovieService $movieService,
    ) {
    }

    protected function adaptMoviesQuery(): MovieCollectionDto
    {
        $titles = $this->movieService->getTitles();

        return new MovieCollectionDto(
            array_map(
                fn (string $title): MovieDto => new MovieDto(title: $title),
                $titles['titles'] ?? [],
            ),
        );
    }

    public static function system(): SystemsEnum
    {
        return SystemsEnum::Baz;
    }
}
