<?php

namespace App\Adapters\Movie;

use App\Dtos\MovieCollectionDto;
use App\Dtos\MovieDto;
use App\Enums\SystemsEnum;
use External\Bar\Movies\MovieService as BarMovieService;

class BarMovieAdapter extends AbstractMovieAdapter
{
    public function __construct(
        protected BarMovieService $movieService,
    ) {
    }

    protected function adaptMoviesQuery(): MovieCollectionDto
    {
        $titles = $this->movieService->getTitles();

        return new MovieCollectionDto(
            array_map(
                fn (array $title): MovieDto => new MovieDto(title: $title['title']),
                $titles['titles'] ?? [],
            ),
        );
    }

    public static function system(): SystemsEnum
    {
        return SystemsEnum::Bar;
    }
}
