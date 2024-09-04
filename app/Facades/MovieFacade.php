<?php

namespace App\Facades;

use App\Dtos\MovieCollectionDto;
use App\Facades\Contracts\MovieContract;
use App\Iterators\Contracts\MovieAdaptersIteratorContract;
use Illuminate\Support\Arr;

class MovieFacade implements MovieContract
{
    public function __construct(
        protected MovieAdaptersIteratorContract $movieAdapterIterator,
    ) {
    }

    public function getTitles(): MovieCollectionDto
    {
        $titles = new MovieCollectionDto([]);

        /** @var \App\Adapters\Movie\Contracts\MovieAdapterContract $adapter */
        foreach ($this->movieAdapterIterator as $adapter) {
            $titles = $titles->merge($adapter->getMovies());
        }

        return $titles;
    }
}
