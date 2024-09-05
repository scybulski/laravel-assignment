<?php

namespace App\Builders\Contracts;

use App\Services\Contracts\RetriableOperationContract;

interface RetriableOperationServiceBuilderContract
{
    public function perform(callable $operation): static;

    public function withParameters(array $parameters): static;

    public function attemptAtMostTimes(int $retries): static;

    public function expectExceptions(array|string $expectedExceptions): static;

    public function throwOnFailure(string $exceptionOnFailure): static;

    public function updateIntervalUsing(callable $intervalBetweenAttemptsCallback): static;

    public function getResult(): RetriableOperationContract;
}
