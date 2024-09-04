<?php

namespace App\Factories;

use App\Adapters\Auth\BarAuthAdapter;
use App\Adapters\Auth\BazAuthAdapter;
use App\Adapters\Auth\Contracts\AuthAdapter;
use App\Adapters\Auth\FooAuthAdapter;
use App\Factories\Contracts\AuthAdapterFactoryContract;
use Illuminate\Support\Str;

class AuthAdapterFactory implements AuthAdapterFactoryContract
{
    public function create(string $login): ?AuthAdapter
    {
        return match (true) {
            Str::startsWith($login, 'FOO_') => app(FooAuthAdapter::class),
            Str::startsWith($login, 'BAR_') => app(BarAuthAdapter::class),
            Str::startsWith($login, 'BAZ_') => app(BazAuthAdapter::class),
            default => null,
        };
    }
}
