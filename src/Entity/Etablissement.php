<?php

namespace App\Entity;

use App\Repository\EtablissementRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=EtablissementRepository::class)
 */
class Etablissement
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=70)
     */
    private $ministere;

    /**
     * @ORM\Column(type="string", length=70)
     */
    private $mandoubia;

    /**
     * @ORM\Column(type="string", length=6)
     */
    private $code;

    /**
     * @ORM\Column(type="string", length=70)
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=70, nullable=true)
     */
    private $ville;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $adresse;

    /**
     * @ORM\Column(type="string", length=70, nullable=true)
     */
    private $tel;

    /**
     * @ORM\Column(type="string", length=70, nullable=true)
     */
    private $fax;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $cle_license;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $nbr_attestation_presence;

    /**
     * @ORM\OneToOne(targetEntity=Gouvernorat::class, cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $gouvernorat;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="etablissements")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    public function __construct()
    {
        $this->code = "0000";
        $this->nom = "المدرسة الإعدادية";
        $this->ville = "تونس";
        $this->ministere = "وزارة التربية";
        $this->mandoubia = "المندوبية الجهوية للتربية";
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMinistere(): ?string
    {
        return $this->ministere;
    }

    public function setMinistere(string $ministere): self
    {
        $this->ministere = $ministere;

        return $this;
    }

    public function getMandoubia(): ?string
    {
        return $this->mandoubia;
    }

    public function setMandoubia(string $mandoubia): self
    {
        $this->mandoubia = $mandoubia;

        return $this;
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

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getVille(): ?string
    {
        return $this->ville;
    }

    public function setVille(?string $ville): self
    {
        $this->ville = $ville;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(?string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getTel(): ?string
    {
        return $this->tel;
    }

    public function setTel(?string $tel): self
    {
        $this->tel = $tel;

        return $this;
    }

    public function getFax(): ?string
    {
        return $this->fax;
    }

    public function setFax(?string $fax): self
    {
        $this->fax = $fax;

        return $this;
    }

    public function getCleLicense(): ?string
    {
        return $this->cle_license;
    }

    public function setCleLicense(?string $cle_license): self
    {
        $this->cle_license = $cle_license;

        return $this;
    }

    public function getNbrAttestationPresence(): ?string
    {
        return $this->nbr_attestation_presence;
    }

    public function setNbrAttestationPresence(?string $nbr_attestation_presence): self
    {
        $this->nbr_attestation_presence = $nbr_attestation_presence;

        return $this;
    }

    public function getGouvernorat(): ?Gouvernorat
    {
        return $this->gouvernorat;
    }

    public function setGouvernorat(Gouvernorat $gouvernorat): self
    {
        $this->gouvernorat = $gouvernorat;

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
