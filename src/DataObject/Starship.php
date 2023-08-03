<?php

namespace William\SevencooksTestTask\DataObject;

use Psr\Log\LoggerInterface;

class Starship
{
    private array $cargo = [];
    private array $pilots = [];
    private string $name;
    private string $model;
    private int $cargoCapacity;
    private string $crewSize;
    private int $length;
    private int $maxSpeed;

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @param string $model
     */
    public function setModel(string $model): void
    {
        $this->model = $model;
    }

    /**
     * @param string $crewSize
     */
    public function setCrewSize(string $crewSize): void
    {
        $this->crewSize = $crewSize;
    }

    /**
     * @param int $length
     */
    public function setLength(int $length): void
    {
        $this->length = $length;
    }

    /**
     * @param int $maxSpeed
     */
    public function setMaxSpeed(int $maxSpeed): void
    {
        $this->maxSpeed = $maxSpeed;
    }

    public function setCargoCapacity(int $cargoCapacity): void
    {
        $this->cargoCapacity = $cargoCapacity;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getModel(): string
    {
        return $this->model;
    }

    public function getCargoCapacity(): int
    {
        return $this->cargoCapacity;
    }

    public function getPilots(): array
    {
        return $this->pilots;
    }

    public function getCrewSize(): string
    {
        return $this->crewSize;
    }

    public function getLength(): int
    {
        return $this->length;
    }

    public function getMaxSpeed(): int
    {
        return $this->maxSpeed;
    }

    public function addPilot(Pilot $pilot): void
    {
        $this->pilots[] = $pilot;
    }

    public function addCargo(Cargo $cargo, LoggerInterface $logger): void
    {
        if ($cargo->getWeight() <= $this->cargoCapacity) {
            $this->cargoCapacity -= $cargo->getWeight();
            $this->cargo[] = $cargo;
        } else {
            $logger->warning(
                sprintf("The Starship %s doesn't have enough cargo space for cargo %s.", $this->name, $cargo->getType()));
        }
    }

    public function getCargo(): array
    {
        return $this->cargo;
    }

    public function getSpeedPercentComparison(int $fastestShipSpeed): int
    {
        return $this->maxSpeed > 0 ? (int)((($fastestShipSpeed - $this->maxSpeed) / $fastestShipSpeed) * 100) : 100;
    }
}