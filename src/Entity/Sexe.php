<?php

namespace App\Entity;

use App\Repository\SexeRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SexeRepository::class)
 */
class Sexe
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=1)
     */
    private $code;

    /**
     * @ORM\Column(type="string", length=1, nullable=true)
     */
    private $code_y;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $libelle_ar;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $libelle_fr;

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

    public function getCodeY(): ?string
    {
        return $this->code_y;
    }

    public function setCodeY(?string $code_y): self
    {
        $this->code_y = $code_y;

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
