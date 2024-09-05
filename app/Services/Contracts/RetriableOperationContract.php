<?php

namespace App\Services\Contracts;

interface RetriableOperationContract
{
    public function execute(): mixed;

    public function setOperation(callable $operation): void;

    public function setParameters(array $parameters): void;

    public function setRetries(int $retries): void;

    public function setExpectedExceptions(array $expectedExceptions): void;

    public function setExceptionOnFailure(string $exceptionOnFailure): void;

    public function setIntervalBetweenAttemptsCallback(callable $intervalBetweenAttemptsCallback): void;
}
