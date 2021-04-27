<?php

namespace App\Entity;

use App\Repository\AnneeScolaireRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AnneeScolaireRepository::class)
 */
class AnneeScolaire
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer",precision=4, nullable=false)
     */
    private $annee;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $current;

    /**
     * @ORM\Column(type="string", length=70, nullable=true)
     */
    private $directeur;

    /**
     * @ORM\Column(type="string", length=70, nullable=true)
     */
    private $surveillant;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="anneeScolaires")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;


    public function __toString()
    {
        return $this->annee . "/" . ($this->annee + 1);
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAnnee($str = false)
    {
        //return $this->annee;
        if ($str)
        return $this->annee . " / " . ($this->annee + 1);
    return $this->annee;
    }

    public function setAnnee(int $annee): self
    {
        $this->annee = $annee;

        return $this;
    }

    public function getCurrent(): ?bool
    {
        return $this->current;
    }

    public function setCurrent(?bool $current): self
    {
        $this->current = $current;

        return $this;
    }

    public function getDirecteur(): ?string
    {
        return $this->directeur;
    }

    public function setDirecteur(?string $directeur): self
    {
        $this->directeur = $directeur;

        return $this;
    }

    public function getSurveillant(): ?string
    {
        return $this->surveillant;
    }

    public function setSurveillant(?string $surveillant): self
    {
        $this->surveillant = $surveillant;

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
}
