<?php

namespace App\Entity;

use App\Repository\CarRepository;
use Doctrine\ORM\Mapping as ORM;

use App\Entity\Transportation;

#[ORM\Entity(repositoryClass: CarRepository::class)]
class Car extends Transportation
{

    #[ORM\Column]
    private ?int $seat = null;

    public function getSeat(): ?int
    {
        return $this->seat;
    }

    public function setSeat(int $seat): static
    {
        $this->seat = $seat;

        return $this;
    }
}
