<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 */
class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255, nullable= true)
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=255, nullable= true)
     */
    private $nomPrenom;

    /**
     * @ORM\Column(type="boolean", nullable= true)
     */
    private $enabled;

    /**
     * @ORM\OneToMany(targetEntity=Etablissement::class, mappedBy="user")
     */
    private $etablissements;

    /**
     * @ORM\OneToMany(targetEntity=AnneeScolaire::class, mappedBy="user")
     */
    private $anneeScolaires;

    /**
     * @ORM\OneToMany(targetEntity=JourFerie::class, mappedBy="user")
     */
    private $jourFeries;

    /**
     * @ORM\OneToMany(targetEntity=Matiere::class, mappedBy="user")
     */
    private $matieres;

    /**
     * @ORM\OneToMany(targetEntity=Enseignant::class, mappedBy="user")
     */
    private $enseignants;

    /**
     * @ORM\OneToMany(targetEntity=Section::class, mappedBy="user")
     */
    private $sections;

    /**
     * @ORM\OneToMany(targetEntity=Niveau::class, mappedBy="user")
     */
    private $niveaux;

    /**
     * @ORM\OneToMany(targetEntity=Classe::class, mappedBy="user")
     */
    private $classes;

    /**
     * @ORM\OneToMany(targetEntity=Eleve::class, mappedBy="user")
     */
    private $eleves;

    /**
     * @ORM\OneToMany(targetEntity=MembreConseil::class, mappedBy="user")
     */
    private $membreConseils;

    /**
     * @ORM\OneToMany(targetEntity=PunitionsAbsences::class, mappedBy="created_by")
     */
    private $punitionsAbsences;

    public function __construct()
    {
        $this->annees = new ArrayCollection();
        $this->etablissements = new ArrayCollection();
        $this->anneeScolaires = new ArrayCollection();
        $this->jourFeries = new ArrayCollection();
        $this->matieres = new ArrayCollection();
        $this->enseignants = new ArrayCollection();
        $this->sections = new ArrayCollection();
        $this->niveaux = new ArrayCollection();
        $this->classes = new ArrayCollection();
        $this->eleves = new ArrayCollection();
        $this->membreConseils = new ArrayCollection();
        $this->punitionsAbsences = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getNomPrenom(): ?string
    {
        return $this->nomPrenom;
    }

    public function setNomPrenom(string $nomPrenom): self
    {
        $this->nomPrenom = $nomPrenom;

        return $this;
    }

    public function getEnabled(): ?bool
    {
        return $this->enabled;
    }

    public function setEnabled(bool $enabled): self
    {
        $this->enabled = $enabled;

        return $this;
    }


    /**
     * @return Collection|Etablissement[]
     */
    public function getEtablissements(): Collection
    {
        return $this->etablissements;
    }

    public function addEtablissement(Etablissement $etablissement): self
    {
        if (!$this->etablissements->contains($etablissement)) {
            $this->etablissements[] = $etablissement;
            $etablissement->setUser($this);
        }

        return $this;
    }

    public function removeEtablissement(Etablissement $etablissement): self
    {
        if ($this->etablissements->removeElement($etablissement)) {
            // set the owning side to null (unless already changed)
            if ($etablissement->getUser() === $this) {
                $etablissement->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|AnneeScolaire[]
     */
    public function getAnneeScolaires(): Collection
    {
        return $this->anneeScolaires;
    }

    public function addAnneeScolaire(AnneeScolaire $anneeScolaire): self
    {
        if (!$this->anneeScolaires->contains($anneeScolaire)) {
            $this->anneeScolaires[] = $anneeScolaire;
            $anneeScolaire->setUser($this);
        }

        return $this;
    }

    public function removeAnneeScolaire(AnneeScolaire $anneeScolaire): self
    {
        if ($this->anneeScolaires->removeElement($anneeScolaire)) {
            // set the owning side to null (unless already changed)
            if ($anneeScolaire->getUser() === $this) {
                $anneeScolaire->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|JourFerie[]
     */
    public function getJourFeries(): Collection
    {
        return $this->jourFeries;
    }

    public function addJourFery(JourFerie $jourFery): self
    {
        if (!$this->jourFeries->contains($jourFery)) {
            $this->jourFeries[] = $jourFery;
            $jourFery->setUser($this);
        }

        return $this;
    }

    public function removeJourFery(JourFerie $jourFery): self
    {
        if ($this->jourFeries->removeElement($jourFery)) {
            // set the owning side to null (unless already changed)
            if ($jourFery->getUser() === $this) {
                $jourFery->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Matiere[]
     */
    public function getMatieres(): Collection
    {
        return $this->matieres;
    }

    public function addMatiere(Matiere $matiere): self
    {
        if (!$this->matieres->contains($matiere)) {
            $this->matieres[] = $matiere;
            $matiere->setUser($this);
        }

        return $this;
    }

    public function removeMatiere(Matiere $matiere): self
    {
        if ($this->matieres->removeElement($matiere)) {
            // set the owning side to null (unless already changed)
            if ($matiere->getUser() === $this) {
                $matiere->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Enseignant[]
     */
    public function getEnseignants(): Collection
    {
        return $this->enseignants;
    }

    public function addEnseignant(Enseignant $enseignant): self
    {
        if (!$this->enseignants->contains($enseignant)) {
            $this->enseignants[] = $enseignant;
            $enseignant->setUser($this);
        }

        return $this;
    }

    public function removeEnseignant(Enseignant $enseignant): self
    {
        if ($this->enseignants->removeElement($enseignant)) {
            // set the owning side to null (unless already changed)
            if ($enseignant->getUser() === $this) {
                $enseignant->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Section[]
     */
    public function getSections(): Collection
    {
        return $this->sections;
    }

    public function addSection(Section $section): self
    {
        if (!$this->sections->contains($section)) {
            $this->sections[] = $section;
            $section->setUser($this);
        }

        return $this;
    }

    public function removeSection(Section $section): self
    {
        if ($this->sections->removeElement($section)) {
            // set the owning side to null (unless already changed)
            if ($section->getUser() === $this) {
                $section->setUser(null);
            }
        }

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
            $niveau->setUser($this);
        }

        return $this;
    }

    public function removeNiveau(Niveau $niveau): self
    {
        if ($this->niveaux->removeElement($niveau)) {
            // set the owning side to null (unless already changed)
            if ($niveau->getUser() === $this) {
                $niveau->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Classe[]
     */
    public function getClasses(): Collection
    {
        return $this->classes;
    }

    public function addClass(Classe $class): self
    {
        if (!$this->classes->contains($class)) {
            $this->classes[] = $class;
            $class->setUser($this);
        }

        return $this;
    }

    public function removeClass(Classe $class): self
    {
        if ($this->classes->removeElement($class)) {
            // set the owning side to null (unless already changed)
            if ($class->getUser() === $this) {
                $class->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Eleve[]
     */
    public function getEleves(): Collection
    {
        return $this->eleves;
    }

    public function addElefe(Eleve $elefe): self
    {
        if (!$this->eleves->contains($elefe)) {
            $this->eleves[] = $elefe;
            $elefe->setUser($this);
        }

        return $this;
    }

    public function removeElefe(Eleve $elefe): self
    {
        if ($this->eleves->removeElement($elefe)) {
            // set the owning side to null (unless already changed)
            if ($elefe->getUser() === $this) {
                $elefe->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|MembreConseil[]
     */
    public function getMembreConseils(): Collection
    {
        return $this->membreConseils;
    }

    public function addMembreConseil(MembreConseil $membreConseil): self
    {
        if (!$this->membreConseils->contains($membreConseil)) {
            $this->membreConseils[] = $membreConseil;
            $membreConseil->setUser($this);
        }

        return $this;
    }

    public function removeMembreConseil(MembreConseil $membreConseil): self
    {
        if ($this->membreConseils->removeElement($membreConseil)) {
            // set the owning side to null (unless already changed)
            if ($membreConseil->getUser() === $this) {
                $membreConseil->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|PunitionsAbsences[]
     */
    public function getPunitionsAbsences(): Collection
    {
        return $this->punitionsAbsences;
    }

    public function addPunitionsAbsence(PunitionsAbsences $punitionsAbsence): self
    {
        if (!$this->punitionsAbsences->contains($punitionsAbsence)) {
            $this->punitionsAbsences[] = $punitionsAbsence;
            $punitionsAbsence->setCreatedBy($this);
        }

        return $this;
    }

    public function removePunitionsAbsence(PunitionsAbsences $punitionsAbsence): self
    {
        if ($this->punitionsAbsences->removeElement($punitionsAbsence)) {
            // set the owning side to null (unless already changed)
            if ($punitionsAbsence->getCreatedBy() === $this) {
                $punitionsAbsence->setCreatedBy(null);
            }
        }

        return $this;
    }
}
