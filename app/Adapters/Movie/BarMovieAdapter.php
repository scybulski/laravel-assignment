<?php

namespace App\Adapters\Movie;

use App\Builders\Contracts\RetriableOperationServiceBuilderContract;
use App\Dtos\MovieCollectionDto;
use App\Dtos\MovieDto;
use App\Enums\SystemsEnum;
use App\Exceptions\ServiceUnavailableException;
use External\Bar\Exceptions\ServiceUnavailableException as ExternalServiceUnavailableException;
use External\Bar\Movies\MovieService as BarMovieService;

class BarMovieAdapter extends AbstractMovieAdapter
{
    public function __construct(
        protected BarMovieService $movieService,
        protected RetriableOperationServiceBuilderContract $retriableOperationServiceBuilder,
    ) {
    }

    protected function adaptMoviesQuery(): MovieCollectionDto
    {
        $retriableOperation = $this->retriableOperationServiceBuilder
            ->perform(fn (): array => $this->movieService->getTitles())
            ->attemptAtMostTimes(4)
            ->throwOnFailure(ServiceUnavailableException::class)
            ->expectExceptions(ExternalServiceUnavailableException::class)
            ->updateIntervalUsing(fn (int $currentInterval, int $currentAttempt): int => $currentAttempt + $currentInterval)
            ->getResult();

        /** @var array $titles */
        $titles = $retriableOperation->execute();

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
