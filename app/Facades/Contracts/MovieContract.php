<?php

namespace App\Facades\Contracts;

use App\Dtos\MovieCollectionDto;

interface MovieContract
{
    public function getTitles(): MovieCollectionDto;
}
