<?php

namespace App\Entity;

use App\Repository\SectionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SectionRepository::class)
 */
class Section
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=5)
     */
    private $code;

    /**
     * @ORM\Column(type="string", length=40, nullable=true)
     */
    private $libelle_ar;

    /**
     * @ORM\Column(type="string", length=40, nullable=true)
     */
    private $libelle_fr;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="sections")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity=Niveau::class, mappedBy="section")
     */
    private $niveaux;

    public function __toString()
    {
        return $this->libelle_ar;
    }
    
    public function __construct()
    {
        $this->niveaux = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getLibelleAr(): ?string
    {
        return $this->libelle_ar;
    }

    public function setLibelleAr(?string $libelle_ar): self
    {
        $this->libelle_ar = $libelle_ar;

        return $this;
    }

    public function getLibelleFr(): ?string
    {
        return $this->libelle_fr;
    }

    public function setLibelleFr(?string $libelle_fr): self
    {
        $this->libelle_fr = $libelle_fr;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection|Niveau[]
     */
    public function getNiveaux(): Collection
    {
        return $this->niveaux;
    }

    public function addNiveau(Niveau $niveau): self
    {
        if (!$this->niveaux->contains($niveau)) {
            $this->niveaux[] = $niveau;
            $niveau->setSection($this);
        }

        return $this;
    }

    public function removeNiveau(Niveau $niveau): self
    {
        if ($this->niveaux->removeElement($niveau)) {
            // set the owning side to null (unless already changed)
            if ($niveau->getSection() === $this) {
                $niveau->setSection(null);
            }
        }

        return $this;
    }
}
