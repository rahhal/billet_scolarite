<?php

namespace App\Entity\Search;

use Doctrine\Common\Collections\ArrayCollection;

class UserSearch
{
    /** @var string|null */
    private $nomPrenom;

    /** @var string|null */
    private $username;

    /** @var string|null */
    private $email;

    /** @var bool|null */
    private $enabled;

    /**
     * @return null|string
     */
    public function getNomPrenom()
    {
        return $this->nomPrenom;
    }

    /**
     * @param null|string $nom_prenom
     */
    public function setNomPrenom($nom_prenom)
    {
        $this->nom_prenom = $nom_prenom;
    }

    /**
     * @return null|string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param null|string $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     * @return null|string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param null|string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return bool|null
     */
    public function getEnabled()
    {
        return $this->enabled;
    }

    /**
     * @param bool|null $enabled
     */
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;
    }


}
