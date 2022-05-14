<?php

namespace App\Entity;

use App\Repository\RightsownerRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=RightsownerRepository::class)
 */
class Rightsowner
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity=Episode::class, inversedBy="rightsowner", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $Episode;

    /**
     * @ORM\ManyToOne(targetEntity=Studio::class, inversedBy="rightsowners")
     * @ORM\JoinColumn(nullable=false)
     */
    private $Studio;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEpisode(): ?Episode
    {
        return $this->Episode;
    }

    public function setEpisode(Episode $Episode): self
    {
        $this->Episode = $Episode;

        return $this;
    }

    public function getStudio(): ?Studio
    {
        return $this->Studio;
    }

    public function setStudio(?Studio $Studio): self
    {
        $this->Studio = $Studio;

        return $this;
    }
}
