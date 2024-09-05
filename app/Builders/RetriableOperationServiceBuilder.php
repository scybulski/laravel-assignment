<?php

namespace App\Builders;

use App\Builders\Contracts\RetriableOperationServiceBuilderContract;
use App\Services\Contracts\RetriableOperationContract;

class RetriableOperationServiceBuilder implements RetriableOperationServiceBuilderContract
{
    protected RetriableOperationContract $retriableOperationService;

    public function __construct(
    ) {
        $this->init();
    }

    public function init(): static
    {
        $this->retriableOperationService = app(RetriableOperationContract::class);

        return $this;
    }

    public function perform(callable $operation): static
    {
        $this->retriableOperationService->setOperation($operation);

        return $this;
    }

    public function withParameters(array $parameters): static
    {
        $this->retriableOperationService->setParameters($parameters);

        return $this;
    }

    public function attemptAtMostTimes(int $retries): static
    {
        $this->retriableOperationService->setRetries($retries);

        return $this;
    }

    public function attemptTwice(): static
    {
        return $this->attemptAtMostTimes(2);
    }

    public function expectExceptions(array|string $expectedExceptions): static
    {
        $expectedExceptions = is_string($expectedExceptions) ? [$expectedExceptions] : $expectedExceptions;

        $this->retriableOperationService->setExpectedExceptions($expectedExceptions);

        return $this;
    }

    public function throwOnFailure(string $exceptionOnFailure): static
    {
        $this->retriableOperationService->setExceptionOnFailure($exceptionOnFailure);

        return $this;
    }

    public function updateIntervalUsing(callable $intervalBetweenAttemptsCallback): static
    {
        $this->retriableOperationService->setIntervalBetweenAttemptsCallback($intervalBetweenAttemptsCallback);

        return $this;
    }

    public function getResult(): RetriableOperationContract
    {
        $result = $this->retriableOperationService;

        $this->init();

        return $result;
    }
}
