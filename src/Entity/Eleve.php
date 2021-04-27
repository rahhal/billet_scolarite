<?php

namespace App\Entity;

use App\Repository\EleveRepository;
use App\Entity\Etablissements;
use App\Entity\Nationalite;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=EleveRepository::class)
 */
class Eleve
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=8, nullable=true)
     */
    private $cin;

    /**
     * @ORM\Column(type="string", length=8)
     */
    private $identifiant;

    /**
     * @ORM\Column(type="string", length=60)
     */
    private $nomprenom;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $sexe;

    /**  
     * @ORM\Column(type="date", nullable=true)
     */
    private $date_naissance;

    /**
     * @ORM\Column(type="string", length=30, nullable=true)
     */
    private $lieu_naissance;

    /**
     * @ORM\ManyToOne(targetEntity=Nationalite::class)
     */
    private $nationalite;

    /**
     * @ORM\ManyToOne(targetEntity=Classe::class)
     * @ORM\JoinColumn(nullable=true)
     */
    private $classe_annee_derniere;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $nbr_frere_secondaire;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $nbr_frere_universitaire;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $est_orphelin = 0;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $orphelin_qui;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $parent_divorce = 0;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $garde;

    /**
     * @ORM\Column(type="string", length=60, nullable=true)
     */
    private $nom_pere;

    /**
     * @ORM\Column(type="string", length=30, nullable=true)
     */
    private $metier_pere='مهن أخرى';

    /**
     * @ORM\Column(type="string", length=8, nullable=true)
     */
    private $cin_pere;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $date_emession_cin;

    /**
     * @ORM\Column(type="string", length=60, nullable=true)
     */
    private $nom_mere;

    /**
     * @ORM\Column(type="string", length=30, nullable=true)
     */
    private $metier_mere='مهن أخرى';

    /**
     * @ORM\Column(type="string", length=120, nullable=true)
     */
    private $adresse_domicile;

    /**
     * @ORM\Column(type="string", length=120, nullable=true)
     */
    private $adresse_travail;

    /**
     * @ORM\Column(type="string", length=8, nullable=true)
     */
    private $fixe;

    /**
     * @ORM\Column(type="string", length=8, nullable=true)
     */
    private $gsm;

    /**
     * @ORM\Column(type="string", length=30, nullable=true)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=60, nullable=true)
     */
    private $procureur;

    /**
     * @ORM\Column(type="string", length=8, nullable=true)
     */
    private $ci_procureur;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $num_ordre;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $num_ordre_u;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="eleves")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=Etablissements::class)
     */
    private $etablissementAnneeDerniere;

    /**
     * @ORM\ManyToOne(targetEntity=Classe::class, inversedBy="eleves")
     */
    private $classeAnneeActuelle;


    public function __toString()
    {
        return "{$this->num_ordre} - {$this->nomprenom}";
    }

    

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCin(): ?string
    {
        return $this->cin;
    }

    public function setCin(?string $cin): self
    {
        $this->cin = $cin;

        return $this;
    }

    public function getIdentifiant(): ?string
    {
        return $this->identifiant;
    }

    public function setIdentifiant(string $identifiant): self
    {
        $this->identifiant = $identifiant;

        return $this;
    }

    public function getNomprenom(): ?string
    {
        return $this->nomprenom;
    }

    public function setNomprenom(string $nomprenom): self
    {
        $this->nomprenom = $nomprenom;

        return $this;
    }

    public function getSexe(): ?int
    {
        return $this->sexe;
    }

    public function setSexe(?int $sexe): self
    {
        $this->sexe = $sexe;

        return $this;
    }

    public function getDateNaissance(): ?\DateTimeInterface
    {
        return $this->date_naissance;
    }

    public function setDateNaissance(?\DateTimeInterface $date_naissance): self
    {
        $this->date_naissance = $date_naissance;

        return $this;
    }

    public function getLieuNaissance(): ?string
    {
        return $this->lieu_naissance;
    }

    public function setLieuNaissance(?string $lieu_naissance): self
    {
        $this->lieu_naissance = $lieu_naissance;

        return $this;
    }

    public function getNationalite(): ?Nationalite
    {
        return $this->nationalite;
    }

    public function setNationalite(?Nationalite $nationalite): self
    {
        $this->nationalite = $nationalite;

        return $this;
    }

    public function getClasseAnneeDerniere(): ?Classe
    {
        return $this->classe_annee_derniere;
    }

    public function setClasseAnneeDerniere(?Classe $classe_annee_derniere): self
    {
        $this->classe_annee_derniere = $classe_annee_derniere;

        return $this;
    }



    public function getNbrFrereSecondaire(): ?int
    {
        return $this->nbr_frere_secondaire;
    }

    public function setNbrFrereSecondaire(?int $nbr_frere_secondaire): self
    {
        $this->nbr_frere_secondaire = $nbr_frere_secondaire;

        return $this;
    }

    public function getNbrFrereUniversitaire(): ?int
    {
        return $this->nbr_frere_universitaire;
    }

    public function setNbrFrereUniversitaire(?int $nbr_frere_universitaire): self
    {
        $this->nbr_frere_universitaire = $nbr_frere_universitaire;

        return $this;
    }

    public function getEstOrphelin(): ?bool
    {
        return $this->est_orphelin;
    }

    public function setEstOrphelin(?bool $est_orphelin): self
    {
        $this->est_orphelin = $est_orphelin;

        return $this;
    }

    public function getOrphelinQui(): ?int
    {
        return $this->orphelin_qui;
    }

    public function setOrphelinQui(?int $orphelin_qui): self
    {
        $this->orphelin_qui = $orphelin_qui;

        return $this;
    }

    public function getParentDivorce(): ?bool
    {
        return $this->parent_divorce;
    }

    public function setParentDivorce(?bool $parent_divorce): self
    {
        $this->parent_divorce = $parent_divorce;

        return $this;
    }

    public function getGarde(): ?int
    {
        return $this->garde;
    }

    public function setGarde(?int $garde): self
    {
        $this->garde = $garde;

        return $this;
    }

    public function getNomPere(): ?string
    {
        return $this->nom_pere;
    }

    public function setNomPere(?string $nom_pere): self
    {
        $this->nom_pere = $nom_pere;

        return $this;
    }

    public function getMetierPere(): ?string
    {
        return $this->metier_pere;
    }

    public function setMetierPere(?string $metier_pere): self
    {
        $this->metier_pere = $metier_pere;

        return $this;
    }

    public function getCinPere(): ?string
    {
        return $this->cin_pere;
    }

    public function setCinPere(?string $cin_pere): self
    {
        $this->cin_pere = $cin_pere;

        return $this;
    }

    public function getDateEmessionCin(): ?\DateTimeInterface
    {
        return $this->date_emession_cin;
    }

    public function setDateEmessionCin(?\DateTimeInterface $date_emession_cin): self
    {
        $this->date_emession_cin = $date_emession_cin;

        return $this;
    }

    public function getNomMere(): ?string
    {
        return $this->nom_mere;
    }

    public function setNomMere(?string $nom_mere): self
    {
        $this->nom_mere = $nom_mere;

        return $this;
    }

    public function getMetierMere(): ?string
    {
        return $this->metier_mere;
    }

    public function setMetierMere(?string $metier_mere): self
    {
        $this->metier_mere = $metier_mere;

        return $this;
    }

    public function getAdresseDomicile(): ?string
    {
        return $this->adresse_domicile;
    }

    public function setAdresseDomicile(?string $adresse_domicile): self
    {
        $this->adresse_domicile = $adresse_domicile;

        return $this;
    }

    public function getAdresseTravail(): ?string
    {
        return $this->adresse_travail;
    }

    public function setAdresseTravail(?string $adresse_travail): self
    {
        $this->adresse_travail = $adresse_travail;

        return $this;
    }

    public function getFixe(): ?string
    {
        return $this->fixe;
    }

    public function setFixe(?string $fixe): self
    {
        $this->fixe = $fixe;

        return $this;
    }

    public function getGsm(): ?string
    {
        return $this->gsm;
    }

    public function setGsm(?string $gsm): self
    {
        $this->gsm = $gsm;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getProcureur(): ?string
    {
        return $this->procureur;
    }

    public function setProcureur(?string $procureur): self
    {
        $this->procureur = $procureur;

        return $this;
    }

    public function getCiProcureur(): ?string
    {
        return $this->ci_procureur;
    }

    public function setCiProcureur(?string $ci_procureur): self
    {
        $this->ci_procureur = $ci_procureur;

        return $this;
    }

    public function getNumOrdre(): ?int
    {
        return $this->num_ordre;
    }

    public function setNumOrdre(?int $num_ordre): self
    {
        $this->num_ordre = $num_ordre;

        return $this;
    }

    public function getNumOrdreU(): ?int
    {
        return $this->num_ordre_u;
    }

    public function setNumOrdreU(?int $num_ordre_u): self
    {
        $this->num_ordre_u = $num_ordre_u;

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

    public function getEtablissementAnneeDerniere(): ?Etablissements
    {
        return $this->etablissementAnneeDerniere;
    }

    public function setEtablissementAnneeDerniere(?Etablissements $etablissementAnneeDerniere): self
    {
        $this->etablissementAnneeDerniere = $etablissementAnneeDerniere;

        return $this;
    }
    public function getClasse(){
        return $this->classeAnneeActuelle;
    }

    public function getClasseAnneeActuelle(): ?Classe
    {
        return $this->classeAnneeActuelle;
    }

    public function setClasseAnneeActuelle(?Classe $classeAnneeActuelle): self
    {
        $this->classeAnneeActuelle = $classeAnneeActuelle;

        return $this;
    }
}
