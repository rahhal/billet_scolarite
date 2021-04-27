<?php

namespace App\Entity;

use App\Repository\PunitionsAbsencesRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass=PunitionsAbsencesRepository::class)
 */
class PunitionsAbsences
{

    const ABSENCE = 'absence';
    const EXCLUSION = 'exclusion';
    const RETARD = 'retard';
    const AVERTISSEMENT = 'avertissement';
    const EXPULSION = 'expulsion';
    const CONSEIL = 'conseil';
    const ELIMINE = 'elimine';
    const BILLET = 'billet';

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $type;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $raison;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $objet;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateDebut;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateFin;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateImpression;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateNotification1;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateNotification2;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateNotification3;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $nbrHeure;

    /**
     * @ORM\Column(type="time", nullable=true)
     */
    private $heure;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $nbrJour;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $modeReglement;

    /**
     * @ORM\ManyToOne(targetEntity=PunitionsAbsences::class)
     */
    private $punitions_absences;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="punitionsAbsences")
     * @ORM\JoinColumn(nullable=true)
     */
    private $created_by;

    /**
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime")
     */
    private $created_at;

    /**
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(type="datetime")
     */
    private $updated_at;

    /**
     * @ORM\ManyToOne(targetEntity=AnneeScolaire::class)
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private $annee_scolaire;

    /**
     * @ORM\ManyToOne(targetEntity=Classe::class)
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private $classe;

    /**
     * @ORM\ManyToOne(targetEntity=Eleve::class)
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private $eleve;

    /**
     * @ORM\ManyToOne(targetEntity=Matiere::class)
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private $matiere;

    /**
     * @ORM\ManyToOne(targetEntity=Enseignant::class)
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private $enseignant;


    public function __construct($createdBy)
    {
        $this->createdBy = $createdBy;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getRaison(): ?string
    {
        return $this->raison;
    }

    public function setRaison(?string $raison): self
    {
        $this->raison = $raison;

        return $this;
    }

    public function getObjet(): ?string
    {
        return $this->objet;
    }

    public function setObjet(?string $objet): self
    {
        $this->objet = $objet;

        return $this;
    }

    public function getDateDebut(): ?\DateTimeInterface
    {
        return $this->dateDebut;
    }

    public function setDateDebut(\DateTimeInterface $dateDebut): self
    {
        $this->dateDebut = $dateDebut;

        return $this;
    }

    public function getDateFin()
    {
         if (!is_null($this->dateFin))
            return $this->dateFin;
        elseif ($this->type == $this::ABSENCE and $this->dateDebut<new\DateTime())
            return (new\DateTime());
        else
            return $this->dateDebut;
    }

    public function setDateFin($dateFin)
    {
        $this->dateFin = $dateFin;

        return $this;
    }

    public function fullCalendar($colors, $jourFerie=null)
    {
        $events = [];
        $notif=0;
        if($this->dateNotification1)
            $notif=1;
        if($this->dateNotification2)
            $notif=2;
        if($this->dateNotification3)
            $notif=3;
        $_event = [
            'id' => $this->id,
            'title' => $this->type,
            'action' => "show",
            'reglee' => !is_null($this->modeReglement),
            'color' => $colors[$this->type],
            'debut' => $this->dateDebut->format('d/m/Y'),
            'notif' => $notif,
            'eleve' => $this->getEleve()->getNomprenom(),
            'classe' => $this->getEleve()->getClasse()->getLibelle(),
            'resourceId' => $this->getEleve()->getId()
        ];
        if ($this->objet)
            $_event['description'] = $this->objet;
        if ($this->raison)
            $_event['description'] = $this->raison;

        foreach ($this->intervalDate($jourFerie) as $item) {
            $_event['start'] = $item['dateDebut']->format('Y-m-d');
            $_event['end'] = $item['dateFin']->modify('+1 day')->format('Y-m-d');
            $events[] = $_event;
        }

        return $events;
    }
    public function getNbrJoursAbsence($jourFerie)
    {
        $calendar = $this->fullCalendar(['absence' => 'red'], $jourFerie);
        $nbr = 0;
        foreach ($calendar as $item)
            $nbr += (new\DateTime($item['start']))->diff(new\DateTime($item['end']))->days;
        return $nbr;
    }

    function getDatesFromRange($start, $end)
    {
        $dates = array($start);
        while (end($dates) < $end) {
            $dates[] = date('Y-m-d', strtotime(end($dates) . ' +1 day'));
        }
        return $dates;
    }

    function jourIllimine($jour, $intervales)
    {
        foreach ($intervales as $range)
            if ($jour >= $range->getDebut() and $jour <= $range->getFin())
                return true;

        return false;
    }

    public function intervalDate($jourFerie = null)
    {
        if (is_null($jourFerie))
            return [[
                'dateDebut' => $this->getDateDebut(),
                'dateFin' => $this->getDateFin()
            ]];

        //-----------------------------------------------------------------------------------------
        $rangDate = self::getDatesFromRange($this->getDateDebut()->format('Y-m-d'), $this->getDateFin()->format('Y-m-d'));
        $i = 0;
        $etat_tableau = 'fermée';
        $tableau = array();
        $_jour = null;
        foreach ($rangDate as $jour) {
            $oJour = \DateTime::createFromFormat('Y-m-j H:i:s', $jour . ' 00:00:00');
            if (self::jourIllimine($oJour, $jourFerie)) {
                if ($etat_tableau == 'ouvert') {
                    $tableau[$i]['dateFin'] = $_jour;
                    $i += 1;
                    $etat_tableau = 'fermée';
                }
            } else {
                if ($etat_tableau == 'fermée') {
                    $tableau[$i]['dateDebut'] = $oJour;
                    $etat_tableau = 'ouvert';
                }
            }
            $_jour = $oJour;
        }

        if ($etat_tableau == 'ouvert')
            $tableau[$i]['dateFin'] = $_jour;

        return $tableau;
    }

    public function __toString()
    {
        return $this->getEleve()->getNomprenom();
    }

    public function getDateImpression(): ?\DateTimeInterface
    {
        return $this->dateImpression;
    }

    public function setDateImpression(?\DateTimeInterface $dateImpression): self
    {
        $this->dateImpression = $dateImpression;

        return $this;
    }

    public function getDateNotification1(): ?\DateTimeInterface
    {
        return $this->dateNotification1;
    }

    public function setDateNotification1(?\DateTimeInterface $dateNotification1): self
    {
        $this->dateNotification1 = $dateNotification1;

        return $this;
    }

    public function getDateNotification2(): ?\DateTimeInterface
    {
        return $this->dateNotification2;
    }

    public function setDateNotification2(?\DateTimeInterface $dateNotification2): self
    {
        $this->dateNotification2 = $dateNotification2;

        return $this;
    }

    public function getDateNotification3(): ?\DateTimeInterface
    {
        return $this->dateNotification3;
    }

    public function setDateNotification3(?\DateTimeInterface $dateNotification3): self
    {
        $this->dateNotification3 = $dateNotification3;

        return $this;
    }

    public function getNbrHeure(): ?int
    {
        return $this->nbrHeure;
    }

    public function setNbrHeure(?int $nbrHeure): self
    {
        $this->nbrHeure = $nbrHeure;

        return $this;
    }

    public function getHeure(): ?\DateTimeInterface
    {
        return $this->heure;
    }

    public function setHeure(?\DateTimeInterface $heure): self
    {
        $this->heure = $heure;

        return $this;
    }

    public function getNbrJour(): ?int
    {
        return $this->nbrJour;
    }

    public function setNbrJour(?int $nbrJour): self
    {
        $this->nbrJour = $nbrJour;

        return $this;
    }

    public function getModeReglement(): ?string
    {
        return $this->modeReglement;
    }

    public function setModeReglement(?string $modeReglement): self
    {
        $this->modeReglement = $modeReglement;

        return $this;
    }

    public function getPunitionsAbsences(): ?self
    {
        return $this->punitions_absences;
    }

    public function setPunitionsAbsences(?self $punitions_absences): self
    {
        $this->punitions_absences = $punitions_absences;

        return $this;
    }

    public function getCreatedBy(): ?User
    {
        return $this->created_by;
    }

    public function setCreatedBy(?User $created_by): self
    {
        $this->created_by = $created_by;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(\DateTimeInterface $updated_at): self
    {
        $this->updated_at = $updated_at;

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

    public function getClasse(): ?Classe
    {
        return $this->classe;
    }

    public function setClasse(?Classe $classe): self
    {
        $this->classe = $classe;

        return $this;
    }

    public function getEleve(): ?Eleve
    {
        return $this->eleve;
    }

    public function setEleve(?Eleve $eleve): self
    {
        $this->eleve = $eleve;

        return $this;
    }

    public function getMatiere(): ?Matiere
    {
        return $this->matiere;
    }

    public function setMatiere(?Matiere $matiere): self
    {
        $this->matiere = $matiere;

        return $this;
    }

    public function getEnseignant(): ?Enseignant
    {
        return $this->enseignant;
    }

    public function setEnseignant(?Enseignant $enseignant): self
    {
        $this->enseignant = $enseignant;

        return $this;
    }
}
