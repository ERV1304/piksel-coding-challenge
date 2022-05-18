<?php

namespace App\Entity;

use App\Repository\StudioRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=StudioRepository::class)
 */
class Studio
{
    /**
     * @ORM\Id
     * @ORM\Column(type="string", length=35)
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\OneToOne(targetEntity=Royalty::class, mappedBy="studio", cascade={"persist", "remove"})
     */
    private $royalty;

    /**
     * @ORM\OneToMany(targetEntity=Rightsowner::class, mappedBy="Studio")
     */
    private $rightsowners;

    public function __construct()
    {
        $this->rightsowners = new ArrayCollection();
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function setId(string $id): self
    {
        $this->id = $id;

        return $this;
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

    public function getRoyalty(): ?Royalty
    {
        return $this->royalty;
    }

    public function setRoyalty(Royalty $royalty): self
    {
        // set the owning side of the relation if necessary
        if ($royalty->getStudio() !== $this) {
            $royalty->setStudio($this);
        }

        $this->royalty = $royalty;

        return $this;
    }

    /**
     * @return Collection<int, Rightsowner>
     */
    public function getRightsowners(): Collection
    {
        return $this->rightsowners;
    }

    public function addRightsowner(Rightsowner $rightsowner): self
    {
        if (!$this->rightsowners->contains($rightsowner)) {
            $this->rightsowners[] = $rightsowner;
            $rightsowner->setStudio($this);
        }

        return $this;
    }

    public function removeRightsowner(Rightsowner $rightsowner): self
    {
        if ($this->rightsowners->removeElement($rightsowner)) {
            // set the owning side to null (unless already changed)
            if ($rightsowner->getStudio() === $this) {
                $rightsowner->setStudio(null);
            }
        }

        return $this;
    }
}
