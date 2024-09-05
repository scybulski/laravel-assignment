<?php

namespace App\Services;

use App\Services\Contracts\RetriableOperationContract;
use Closure;
use Exception;
use Illuminate\Support\Arr;
use Throwable;

class RetriableOperationService implements RetriableOperationContract
{
    protected Closure $operation;

    protected array $parameters = [];

    protected int $retries = 3;

    protected array $expectedExceptions = [
        Throwable::class,
    ];

    protected string $exceptionOnFailure = Exception::class;

    protected ?Closure $intervalBetweenAttemptsCallback = null;

    protected int $currentInterval = 1;

    public function execute(): mixed
    {
        for ($retry = 0; $retry < $this->retries; $retry++) {
            try {
                return call_user_func($this->operation, ...$this->parameters);
            } catch (Throwable $exception) {
                Arr::first(
                    $this->expectedExceptions,
                    fn (string $exceptionClass): bool => $exception instanceof $exceptionClass,
                ) ?? throw $exception;
            }

            sleep($this->currentInterval);

            $this->updateInterval($retry);
        }

        throw new $this->exceptionOnFailure();
    }

    public function setOperation(callable $operation): void
    {
        $this->operation = $operation;
    }

    public function setParameters(array $parameters): void
    {
        $this->parameters = $parameters;
    }

    public function setRetries(int $retries): void
    {
        $this->retries = $retries;
    }

    public function setExpectedExceptions(array $expectedExceptions): void
    {
        $this->expectedExceptions = $expectedExceptions;
    }

    public function setExceptionOnFailure(string $exceptionOnFailure): void
    {
        $this->exceptionOnFailure = $exceptionOnFailure;
    }

    public function setIntervalBetweenAttemptsCallback(callable $intervalBetweenAttemptsCallback): void
    {
        $this->intervalBetweenAttemptsCallback = $intervalBetweenAttemptsCallback;
    }

    protected function updateInterval(int $retry): void
    {
        if ($this->intervalBetweenAttemptsCallback) {
            $this->currentInterval = call_user_func($this->intervalBetweenAttemptsCallback, $this->currentInterval, $retry);
        }
    }
}
