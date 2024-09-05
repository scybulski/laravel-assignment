<?php

namespace App\Adapters\Movie\Contracts;

use App\Dtos\MovieCollectionDto;

interface MovieAdapterContract
{
    public function getMovies(): MovieCollectionDto;
}
