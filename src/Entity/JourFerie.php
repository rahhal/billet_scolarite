<?php

namespace App\Entity;

use App\Repository\JourFerieRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=JourFerieRepository::class)
 */
class JourFerie
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="date")
     */
    private $debut;

    /**
     * @ORM\Column(type="date")
     */
    private $fin;

    /**
     * @ORM\ManyToOne(targetEntity=AnneeScolaire::class)
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private $annee_scolaire;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="jourFeries")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\Column(type="string", length=30)
     */
    private $libelle;


    public function fulCalendar($resourceIds)
    {
        $fin = clone $this->fin;
        return [
            'id' => "jf-" . $this->id,
            'title' => $this->libelle,
            'color' => "#9e11d1",
            'overlap' => false,
            'rendering' => 'background',
            'start' => $this->debut->format('Y-m-d'),
            'end' => $fin->modify('+1 day')->format('Y-m-d'),
            'resourceIds' => $resourceIds
        ];
    }
    
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDebut(): ?\DateTimeInterface
    {
        return $this->debut;
    }

    public function setDebut(\DateTimeInterface $debut): self
    {
        $this->debut = $debut;

        return $this;
    }

    public function getFin(): ?\DateTimeInterface
    {
        return $this->fin;
    }

    public function setFin(\DateTimeInterface $fin): self
    {
        $this->fin = $fin;

        return $this;
    }

    public function getAnneeScolaire(): ?AnneeScolaire
    {
        return $this->annee_scolaire;
    }

    public function setAnneeScolaire(?AnneeScolaire $annee_scolaire): self
    {
        $this->annee_scolaire = $annee_scolaire;

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

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): self
    {
        $this->libelle = $libelle;

        return $this;
    }
}
