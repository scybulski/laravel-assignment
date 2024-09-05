<?php

use App\Adapters\Movie\FooMovieAdapter;
use App\Builders\Contracts\RetriableOperationServiceBuilderContract;
use App\Dtos\MovieCollectionDto;
use App\Dtos\MovieDto;
use App\Exceptions\ServiceUnavailableException;
use App\Services\RetriableOperationService;
use External\Foo\Exceptions\ServiceUnavailableException as ExternalServiceUnavailableException;
use External\Foo\Movies\MovieService;
use Illuminate\Support\Facades\Cache;
use Mockery\MockInterface;

it('should retrieve list of movies using retriable operation service', function (): void {
    $movies = [
        'Die Hard 1',
        'Die Hard 3',
    ];

    $externalMovieServiceStub = Mockery::mock(MovieService::class);

    $retriableOperationServiceBuilderMock = Mockery::mock(RetriableOperationServiceBuilderContract::class, function (MockInterface $mock) use ($movies): void {
        $mock->expects('perform')->once();
        $mock->expects('withParameters')->never();
        $mock->expects('attemptAtMostTimes')->atMost(1)->atLeast(0);
        $mock->expects('expectExceptions')->once()->with(ExternalServiceUnavailableException::class);
        $mock->expects('throwOnFailure')->once()->with(ServiceUnavailableException::class);
        $mock->expects('updateIntervalUsing')->atMost(1)->atLeast(0);
        $mock->expects('getResult')->once()->andReturn(
            Mockery::mock(RetriableOperationService::class, function (MockInterface $mock) use ($movies): void {
                $mock->expects('execute')
                    ->once()
                    ->andReturn($movies);
            }),
        );
    });

    $movieAdapter = new FooMovieAdapter($externalMovieServiceStub, $retriableOperationServiceBuilderMock);

    $result = $movieAdapter->getMovies();

    // ensure the same instance
    expect($result)->toBeInstanceOf(MovieCollectionDto::class);

    $resultMovieTitles = array_map(
        fn (MovieDto $movieDto): string => $movieDto->title,
        $result->movies,
    );

    foreach ($movies as $movieTitle) {
        expect($movieTitle)->toBeIn($resultMovieTitles);
    }
});

it('should retrieve list of movies from cache', function (): void {
    $movieCollection = new MovieCollectionDto([]);

    Cache::shouldReceive('remember')
        ->once()
        ->withSomeOfArgs(
            key: 'movies_Foo',
        )
        ->andReturn($movieCollection);

    $externalMovieServiceMock = Mockery::mock(MovieService::class, function (MockInterface $mock): void {
        $mock->expects('getTitles')->never();
    });

    $retriableOperationServiceBuilderMock = Mockery::mock(RetriableOperationServiceBuilderContract::class, function (MockInterface $mock): void {
        $mock->expects('getResult')->never();
    });

    $movieAdapter = new FooMovieAdapter($externalMovieServiceMock, $retriableOperationServiceBuilderMock);

    $result = $movieAdapter->getMovies();

    // ensure the same instance
    expect($result)->toBe($movieCollection);
});

it('should throw exception on repeating external service failures', function (): void {
    $externalMovieServiceMock = Mockery::mock(MovieService::class);

    $retriableOperationServiceBuilderMock = Mockery::mock(RetriableOperationServiceBuilderContract::class, function (MockInterface $mock): void {
        $mock->shouldReceive('perform')->once();
        $mock->shouldReceive('withParameters')->never();
        $mock->shouldReceive('attemptAtMostTimes')->atMost(1)->atLeast(0);
        $mock->shouldReceive('expectExceptions')->once()->with(ExternalServiceUnavailableException::class);
        $mock->shouldReceive('throwOnFailure')->once()->with(ServiceUnavailableException::class);
        $mock->shouldReceive('updateIntervalUsing')->atMost(1)->atLeast(0);
        $mock->shouldReceive('getResult')->once()->andThrow(ServiceUnavailableException::class);
    });

    $movieAdapter = new FooMovieAdapter($externalMovieServiceMock, $retriableOperationServiceBuilderMock);

    expect(fn (): never => $movieAdapter->getMovies())->toThrow(ServiceUnavailableException::class);
});
