<?php

namespace App\Entity;

use App\Repository\ViewingRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ViewingRepository::class)
 */
class Viewing
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Customer::class, inversedBy="viewings")
     * @ORM\JoinColumn(nullable=false)
     */
    private $Customer;

    /**
     * @ORM\ManyToOne(targetEntity=Episode::class, inversedBy="viewings")
     * @ORM\JoinColumn(nullable=false)
     */
    private $Episode;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCustomer(): ?Customer
    {
        return $this->Customer;
    }

    public function setCustomer(?Customer $Customer): self
    {
        $this->Customer = $Customer;

        return $this;
    }

    public function getEpisode(): ?Episode
    {
        return $this->Episode;
    }

    public function setEpisode(?Episode $Episode): self
    {
        $this->Episode = $Episode;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }
}
