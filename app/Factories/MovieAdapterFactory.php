<?php

namespace App\Factories;

use App\Adapters\Movie\Contracts\MovieAdapterContract;
use App\Enums\SystemsEnum;
use App\Factories\Contracts\MovieAdapterFactoryContract;
use App\Adapters\Movie\BarMovieAdapter as BarMovieAdapter;
use App\Adapters\Movie\BazMovieAdapter as BazMovieAdapter;
use App\Adapters\Movie\FooMovieAdapter as FooMovieAdapter;

class MovieAdapterFactory implements MovieAdapterFactoryContract
{
    /**
     * @return class-string
     */
    protected static function getSystemAdapterClass(SystemsEnum $system): string
    {
        return match ($system) {
            SystemsEnum::Foo => FooMovieAdapter::class,
            SystemsEnum::Bar => BarMovieAdapter::class,
            SystemsEnum::Baz => BazMovieAdapter::class,
        };
    }

    public function create(SystemsEnum $system): ?MovieAdapterContract
    {
        $adapterClass = static::getSystemAdapterClass($system);

        return $adapterClass ? app($adapterClass) : null;
    }
}
