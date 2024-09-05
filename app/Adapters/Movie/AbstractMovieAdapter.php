<?php

namespace App\Adapters\Movie;

use App\Adapters\Movie\Contracts\MovieAdapterContract;
use App\Dtos\MovieCollectionDto;
use App\Enums\SystemsEnum;
use Illuminate\Support\Facades\Cache;

abstract class AbstractMovieAdapter implements MovieAdapterContract
{
    public function getMovies(): MovieCollectionDto
    {
        return $this->getCachedMovies();
    }

    protected function getCachedMovies(): MovieCollectionDto
    {
        /**
         * @todo use the Cache::flexible() method once shipped to the framework for deferred cache update
         */
        return Cache::remember(
            key: $this->cacheKey(),
            ttl: now()->addMinutes(10),
            callback: fn (): MovieCollectionDto => $this->adaptMoviesQuery(),
        );
    }

    abstract protected function adaptMoviesQuery(): MovieCollectionDto;

    abstract public static function system(): SystemsEnum;

    protected static function cacheKey(): string
    {
        return 'movies_' . static::system()->name;
    }
}
