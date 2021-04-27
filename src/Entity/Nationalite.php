<?php

namespace App\Entity;

use App\Repository\NationaliteRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=NationaliteRepository::class)
 */
class Nationalite
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=3)
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
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $libelle_court_ar;

    /**
     * @ORM\Column(type="string", length=2, nullable=true)
     */
    private $code_bac;

    public function __toString()
    {
        return $this->libelle_ar;
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

    public function getLibelleCourtAr(): ?string
    {
        return $this->libelle_court_ar;
    }

    public function setLibelleCourtAr(?string $libelle_court_ar): self
    {
        $this->libelle_court_ar = $libelle_court_ar;

        return $this;
    }

    public function getCodeBac(): ?string
    {
        return $this->code_bac;
    }

    public function setCodeBac(?string $code_bac): self
    {
        $this->code_bac = $code_bac;

        return $this;
    }
}
