<?php

namespace App\Entity;

use App\Repository\EpisodeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=EpisodeRepository::class)
 */
class Episode
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\OneToOne(targetEntity=Rightsowner::class, mappedBy="Episode", cascade={"persist", "remove"})
     */
    private $rightsowner;

    /**
     * @ORM\OneToMany(targetEntity=Viewing::class, mappedBy="Episode")
     */
    private $viewings;

    public function __construct()
    {
        $this->viewings = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getRightsowner(): ?Rightsowner
    {
        return $this->rightsowner;
    }

    public function setRightsowner(Rightsowner $rightsowner): self
    {
        // set the owning side of the relation if necessary
        if ($rightsowner->getEpisode() !== $this) {
            $rightsowner->setEpisode($this);
        }

        $this->rightsowner = $rightsowner;

        return $this;
    }

    /**
     * @return Collection<int, Viewing>
     */
    public function getViewings(): Collection
    {
        return $this->viewings;
    }

    public function addViewing(Viewing $viewing): self
    {
        if (!$this->viewings->contains($viewing)) {
            $this->viewings[] = $viewing;
            $viewing->setEpisode($this);
        }

        return $this;
    }

    public function removeViewing(Viewing $viewing): self
    {
        if ($this->viewings->removeElement($viewing)) {
            // set the owning side to null (unless already changed)
            if ($viewing->getEpisode() === $this) {
                $viewing->setEpisode(null);
            }
        }

        return $this;
    }
}
