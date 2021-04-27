<?php

namespace App\Entity;

use App\Repository\EtablissementsRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=EtablissementsRepository::class)
 */
class Etablissements
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=6)
     */
    private $code;

    /**
     * @ORM\Column(type="string", length=70, nullable=true)
     */
    private $libelle;

    /**
     * @ORM\ManyToOne(targetEntity=Gouvernorat::class)
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private $gouvernorat;
    
    public function __toString()
    {
        return "{$this->code} - {$this->libelle} - {$this->getGouvernorat()}";
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

    public function getGouvernorat(): ?Gouvernorat
    {
        return $this->gouvernorat;
    }

    public function setGouvernorat(?Gouvernorat $gouvernorat): self
    {
        $this->gouvernorat = $gouvernorat;

        return $this;
    }

    
}
