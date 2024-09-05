<?php

namespace App\Iterators;

use App\Adapters\Movie\Contracts\MovieAdapterContract;
use App\Enums\SystemsEnum;
use App\Factories\Contracts\MovieAdapterFactoryContract;
use App\Iterators\Contracts\MovieAdaptersIteratorContract;
use Iterator;

class MovieAdaptersIterator implements MovieAdaptersIteratorContract
{
    protected ?int $position;

    public function __construct(
        protected MovieAdapterFactoryContract $movieAdapterFactory,
    ) {
        $this->rewind();
    }

    public function current(): ?MovieAdapterContract
    {
        if (!$this->valid()) {
            return null;
        }

        return $this->movieAdapterFactory->create(
            $this->getSystems()[$this->position],
        );
    }

    public function next(): void
    {
        $this->position++;
    }

    public function key(): ?string
    {
        return $this->getSystems()[$this->position]->name ?? null;
    }

    public function valid(): bool
    {
        return array_key_exists($this->position, $this->getSystems());
    }

    public function rewind(): void
    {
        $this->position = array_key_first($this->getSystems());
    }

    /**
     * @return array<\App\Enums\SystemsEnum>
     */
    protected static function getSystems(): array
    {
        return SystemsEnum::cases();
    }
}
