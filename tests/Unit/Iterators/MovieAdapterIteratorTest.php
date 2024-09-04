<?php

use App\Adapters\Movie\Contracts\MovieAdapterContract;
use App\Enums\SystemsEnum;
use App\Factories\Contracts\MovieAdapterFactoryContract;
use Illuminate\Support\Arr;
use Mockery\MockInterface;

it('should iterate over all systems', function (): void {
    $movieAdapterFactoryMock = Mockery::mock(MovieAdapterFactoryContract::class, function (MockInterface $mock): void {
        $mock->shouldReceive('create')
            ->times(sizeof(SystemsEnum::cases()))
            ->withArgs(fn (SystemsEnum $system): true => true)
            ->andReturn(Mockery::mock(MovieAdapterContract::class));
    });

    $movieAdaptersIterator = new \App\Iterators\MovieAdaptersIterator(
        movieAdapterFactory: $movieAdapterFactoryMock,
    );

    foreach ($movieAdaptersIterator as $movieAdapterKey => $movieAdapter) {
        expect($movieAdapterKey)->toBeIn(Arr::pluck(SystemsEnum::cases(), 'name'));
        expect($movieAdapter)->toBeInstanceOf(MovieAdapterContract::class);
    }
});
