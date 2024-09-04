<?php

use App\Adapters\Movie\FooMovieAdapter;
use App\Dtos\MovieCollectionDto;
use App\Dtos\MovieDto;
use External\Foo\Movies\MovieService;
use Illuminate\Support\Facades\Cache;
use Mockery\MockInterface;

it('should retrieve list of movies', function (): void {
    $movieTitle1 = 'Movie 1';
    $movieTitle2 = 'Movie 2';
    $externalMovieServiceMock = Mockery::mock(MovieService::class, function (MockInterface $mock) use ($movieTitle1, $movieTitle2): void {
        $mock->shouldReceive('getTitles')
            ->once()
            ->andReturn([$movieTitle1, $movieTitle2]);
    });

    $movieAdapter = new FooMovieAdapter($externalMovieServiceMock);

    $result = $movieAdapter->getMovies();

    expect($result)->toBeInstanceOf(MovieCollectionDto::class);

    $movieTitles = array_map(
        fn (MovieDto $movieDto): string => $movieDto->title,
        $result->movies,
    );

    expect($movieTitles)->toContain($movieTitle1, $movieTitle2);
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

    $movieAdapter = new FooMovieAdapter($externalMovieServiceMock);

    $result = $movieAdapter->getMovies();

    // ensure the same instance
    expect($result)->toBe($movieCollection);
});
