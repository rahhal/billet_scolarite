<?php

namespace App\Entity\Search;

use App\Entity\Etablissements;
use App\Entity\Classe;
use App\Entity\User;

class EleveSearch
{
    /** @var string|null */
    private $identifiant;

    /** @var string|null */
    private $nomprenom;

    /** @var Etablissements|null */
    private $etablissementAnneeDerniere;

    /** @var Classe|null */
    private $classeAnneeDerniere;

    /** @var Classe|null */
    private $classeAnneeActuelle;

    /**
     * @var User
     */
    public $user;

    /**
     * @return null|string
     */
    public function getIdentifiant()
    {
        return $this->identifiant;
    }

    /**
     * @param null|string $identifiant
     * @return EleveSearch
     */
    public function setIdentifiant($identifiant)
    {
        $this->identifiant = $identifiant;
        return $this;
    }

    /**
     * @return null|string
     */
    public function getNomprenom(): ?String
    {
        return $this->nomprenom;
    }

    /**
     * @param null|string $nomprenom
     * @return EleveSearch
     */
    public function setNomprenom($nomprenom): EleveSearch
    {
        $this->nomprenom = $nomprenom;
        return $this;
    }

    /**
     * @return Etablissements|null
     */
    public function getEtablissementAnneeDerniere(): ?Etablissements
    {
        return $this->etablissementAnneeDerniere;
    }

    /**
     * @param Etablissements|null $etablissementAnneeDerniere
     * @return EleveSearch
     */
    public function setEtablissementAnneeDerniere($etablissementAnneeDerniere): EleveSearch
    {
        $this->etablissementAnneeDerniere = $etablissementAnneeDerniere;
        return $this;
    }

    /**
     * @return Classe|null
     */
    public function getClasseAnneeDerniere()
    {
        return $this->classeAnneeDerniere;
    }

    /**
     * @param Classe|null $classeAnneeDerniere
     * @return EleveSearch
     */
    public function setClasseAnneeDerniere($classeAnneeDerniere): EleveSearch
    {
        $this->classeAnneeDerniere = $classeAnneeDerniere;
        return $this;
    }

    /**
     * @return Classe|null
     */
    public function getClasseAnneeActuelle(): ?Classe
    {
        return $this->classeAnneeActuelle;
    }

    /**
     * @param Classe|null $classeAnneeActuelle
     * @return EleveSearch
     */
    public function setClasseAnneeActuelle($classeAnneeActuelle)
    {
        $this->classeAnneeActuelle = $classeAnneeActuelle;
        return $this;
    }
    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param null|User $user
     * @return EleveSearch
     */
    public function setUser($user)
    {
        $this->user = $user;
        return $this;
    }

}
