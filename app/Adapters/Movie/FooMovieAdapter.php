<?php

namespace App\Adapters\Movie;

use App\Dtos\MovieCollectionDto;
use App\Dtos\MovieDto;
use App\Enums\SystemsEnum;
use External\Foo\Movies\MovieService as FooMovieService;

class FooMovieAdapter extends AbstractMovieAdapter
{
    public function __construct(
        protected FooMovieService $movieService,
    ) {
    }

    protected function adaptMoviesQuery(): MovieCollectionDto
    {
        $titles = $this->movieService->getTitles();

        return new MovieCollectionDto(
            array_map(
                fn (string $title): MovieDto => new MovieDto(title: $title),
                $titles,
            ),
        );
    }

    public static function system(): SystemsEnum
    {
        return SystemsEnum::Foo;
    }
}
