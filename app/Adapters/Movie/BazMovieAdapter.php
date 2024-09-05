<?php

namespace App\Adapters\Movie;

use App\Builders\Contracts\RetriableOperationServiceBuilderContract;
use App\Dtos\MovieCollectionDto;
use App\Dtos\MovieDto;
use App\Enums\SystemsEnum;
use App\Exceptions\ServiceUnavailableException;
use External\Baz\Exceptions\ServiceUnavailableException as ExternalServiceUnavailableException;
use External\Baz\Movies\MovieService as BazMovieService;

class BazMovieAdapter extends AbstractMovieAdapter
{
    public function __construct(
        protected BazMovieService $movieService,
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
            ->updateIntervalUsing(fn (int $currentInterval, int $currentAttempt): int => ($currentInterval + $currentAttempt) * 2)
            ->getResult();

        /** @var array $titles */
        $titles = $retriableOperation->execute();

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
