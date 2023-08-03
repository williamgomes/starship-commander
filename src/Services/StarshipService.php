<?php

namespace William\SevencooksTestTask\Services;

use William\SevencooksTestTask\DataObject\Starship;

class StarshipService
{
    public function __construct(private Starship $starship)
    {
    }

    public function createAndReturnStarship(array $starshipData): Starship
    {
        $this->starship->setName($starshipData['name']);
        $this->starship->setModel($starshipData['model']);
        $this->starship->setCargoCapacity($starshipData['cargo_capacity']);
        $this->starship->setCrewSize($starshipData['crew']);
        $this->starship->setLength($starshipData['length']);
        $this->starship->setMaxSpeed($starshipData['max_atmosphering_speed']);

        return $this->starship;
    }
}