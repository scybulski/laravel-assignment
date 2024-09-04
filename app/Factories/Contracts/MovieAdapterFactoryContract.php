<?php

namespace App\Factories\Contracts;

use App\Adapters\Movie\Contracts\MovieAdapterContract;
use App\Enums\SystemsEnum;

interface MovieAdapterFactoryContract
{
    public function create(SystemsEnum $system): ?MovieAdapterContract;
}
