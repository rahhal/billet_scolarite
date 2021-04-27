<?php

namespace App\Twig\Extension;

use App\Entity\AnneeScolaire;
use App\Entity\Etablissement;
use Doctrine\ORM\EntityManagerInterface;
use Twig\TwigFunction;
use Twig\TwigFilter;
use Twig\Extension\AbstractExtension;

class GeneraleExtension extends AbstractExtension
{
    private $em;

    /**
     * GeneraleExtension constructor.
     * @param $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('InfoGeneral', [$this, 'InfoGeneral'])
        ];
    }

    public function getFilters()
    {
        return [
            new TwigFilter('viewBooleanValue', [$this, 'viewBooleanValue']),
        ];
    }

    public function InfoGeneral()
    {
        /** @var Etablissement $etablissement */
        $etablissement = $this->em->getRepository(Etablissement::class)->findOneBy([]);
        /** @var AnneeScolaire $annee */
        $annee = $this->em->getRepository(AnneeScolaire::class)->findOneBy(['current' => true]);
        return [
            'ville' => $etablissement->getVille(),
            'etablissement' => $etablissement->getNom(),
            'ministere' => $etablissement->getMinistere(),
            'administration' => $etablissement->getMandoubia(),
            'directeur' => $annee->getDirecteur(),
            'surveillant' => $annee->getSurveillant(),
            'annee'=>$annee->getAnnee()." / ".($annee->getAnnee()+1)
        ];
    }

    public function viewBooleanValue($val)
    {
        if ($val)
            return '<i class="icon-checkmark4" style="color: green"></i>';
        return '<i class="icon-cross2" style="color: red"></i>';
    }
}