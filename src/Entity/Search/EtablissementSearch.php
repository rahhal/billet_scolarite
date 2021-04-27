<?php

namespace App\Entity\Search;

use App\Entity\Gouvernorat;
use App\Entity\User;

class EtablissementSearch
{
    /** @var string|null */
    private $ministere;

    /** @var string|null */
    private $mandoubia;

    /** @var string|null */
    private $code;

    /** @var string|null */
    private $nom;

    /** @var Gouvernorat|null */
    private $gouvernorat;

    /** @var string|null */
    private $ville;

    /** @var string|null */
    private $adresse;

    /** @var string|null */
    private $tel;

    /** @var string|null */
    private $fax;

    /**
     * @var User
     */
    public $user;
    /**
     * @return null|string
     */
    public function getMinistere()
    {
        return $this->ministere;
    }

    /**
     * @param null|string $ministere
     * @return EtablissementSearch
     */
    public function setMinistere($ministere)
    {
        $this->ministere = $ministere;
        return $this;
    }

    /**
     * @return null|string
     */
    public function getMandoubia()
    {
        return $this->mandoubia;
    }

    /**
     * @param null|string $mandoubia
     * @return EtablissementSearch
     */
    public function setMandoubia($mandoubia)
    {
        $this->mandoubia = $mandoubia;
        return $this;
    }

    /**
     * @return null|string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param null|string $code
     * @return EtablissementSearch
     */
    public function setCode($code)
    {
        $this->code = $code;
        return $this;
    }

    /**
     * @return null|string
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * @param null|string $nom
     * @return EtablissementSearch
     */
    public function setNom($nom)
    {
        $this->nom = $nom;
        return $this;
    }

    /**
     * @return Gouvernorat|null
     */
    public function getGouvernorat()
    {
        return $this->gouvernorat;
    }

    /**
     * @param Gouvernorat|null $gouvernorat
     * @return EtablissementSearch
     */
    public function setGouvernorat($gouvernorat)
    {
        $this->gouvernorat = $gouvernorat;
        return $this;
    }

    /**
     * @return null|string
     */
    public function getVille()
    {
        return $this->ville;
    }

    /**
     * @param null|string $ville
     * @return EtablissementSearch
     */
    public function setVille($ville)
    {
        $this->ville = $ville;
        return $this;
    }

    /**
     * @return null|string
     */
    public function getAdresse()
    {
        return $this->adresse;
    }

    /**
     * @param null|string $adresse
     * @return EtablissementSearch
     */
    public function setAdresse($adresse)
    {
        $this->adresse = $adresse;
        return $this;
    }

    /**
     * @return null|string
     */
    public function getTel()
    {
        return $this->tel;
    }

    /**
     * @param null|string $tel
     * @return EtablissementSearch
     */
    public function setTel($tel)
    {
        $this->tel = $tel;
        return $this;
    }

    /**
     * @return null|string
     */
    public function getFax()
    {
        return $this->fax;
    }

    /**
     * @param null|string $fax
     * @return EtablissementSearch
     */
    public function setFax($fax)
    {
        $this->fax = $fax;
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
     * @return EtablissementSearch
     */
    public function setUser($user)
    {
        $this->user = $user;
        return $this;
    }
}
