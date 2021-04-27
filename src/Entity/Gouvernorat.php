<?php

namespace App\Entity;

use App\Repository\GouvernoratRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=GouvernoratRepository::class)
 */
class Gouvernorat
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=2)
     */
    private $code;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $libelle;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $libelle_ar;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $libelle_fr;
    
    public function __toString()
    {
        return $this->libelle;
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

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(?string $libelle): self
    {
        $this->libelle = $libelle;

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
}
