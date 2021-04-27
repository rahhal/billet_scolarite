<?php

namespace App\Entity\Search;


class SectionSearch
{
    /** @var string|null */
    private $code;

    /** @var string|null */
    private $libelleAR;

    /** @var string|null */
    private $libelleFR;

    /**
     * @return null|string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param null|string $code
     * @return SectionSearch
     */
    public function setCode($code)
    {
        $this->code = $code;
        return $this;
    }

    /**
     * @return null|string
     */
    public function getLibelleAR()
    {
        return $this->libelleAR;
    }

    /**
     * @param null|string $libelleAR
     * @return SectionSearch
     */
    public function setLibelleAR($libelleAR)
    {
        $this->libelleAR = $libelleAR;
        return $this;
    }

    /**
     * @return null|string
     */
    public function getLibelleFR()
    {
        return $this->libelleFR;
    }

    /**
     * @param null|string $libelleFR
     * @return SectionSearch
     */
    public function setLibelleFR($libelleFR)
    {
        $this->libelleFR = $libelleFR;
        return $this;
    }


}
