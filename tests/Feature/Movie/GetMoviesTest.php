<?php

use App\Dtos\MovieCollectionDto;
use App\Dtos\MovieDto;
use App\Facades\Contracts\MovieContract;
use Mockery\MockInterface;

it('should receive a list of movies', function () {
    $moviesCollectionDto = new MovieCollectionDto(movies: [
        new MovieDto(title: 'Die Hard'),
        new MovieDto(title: 'Die Hard 2'),
        new MovieDto(title: 'Die Hard 3'),
        new MovieDto(title: 'Die Hard 4'),
        new MovieDto(title: 'Die Hard 5'),
    ]);

    $this->instance(MovieContract::class, Mockery::mock(MovieContract::class, function (MockInterface $mock) use ($moviesCollectionDto): void {
        $mock->expects('getTitles')
            ->once()
            ->andReturn($moviesCollectionDto);
    }));

    /** @var \Illuminate\Testing\TestResponse $response */
    $response = $this->getJson('/api/titles');

    $response->assertOk();
    $response->assertJsonStructure([
        'data' => [
            '*' => [
                'title',
            ],
        ],
    ]);

    /** @var \App\Dtos\MovieDto $movieDto */
    foreach ($moviesCollectionDto->movies as $movieDto) {
        $response->assertJsonFragment([
            $movieDto->title,
        ]);
    }
});
