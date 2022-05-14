<?php

namespace App\Entity;

use App\Repository\RoyaltyRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=RoyaltyRepository::class)
 */
class Royalty
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity=Studio::class, inversedBy="royalty", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $studio;

    /**
     * @ORM\Column(type="float")
     */
    private $payment;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStudio(): ?Studio
    {
        return $this->studio;
    }

    public function setStudio(Studio $studio): self
    {
        $this->studio = $studio;

        return $this;
    }

    public function getPayment(): ?float
    {
        return $this->payment;
    }

    public function setPayment(float $payment): self
    {
        $this->payment = $payment;

        return $this;
    }
}
