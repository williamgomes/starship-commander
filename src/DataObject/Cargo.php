<?php

namespace William\SevencooksTestTask\DataObject;

class Cargo
{
    public function __construct(
        private readonly string $type,
        private readonly int $weight
    )
    {}

    public function getType(): string
    {
        return $this->type;
    }

    public function getWeight(): int
    {
        return $this->weight;
    }
}