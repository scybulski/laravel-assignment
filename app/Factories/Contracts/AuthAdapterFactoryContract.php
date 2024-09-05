<?php

namespace App\Factories\Contracts;

use App\Adapters\Auth\Contracts\AuthAdapter;

interface AuthAdapterFactoryContract
{
    public function create(string $login): ?AuthAdapter;
}
