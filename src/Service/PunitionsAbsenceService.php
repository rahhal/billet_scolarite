<?php

namespace App\Service;

use App\Entity\PunitionsAbsences;
use App\Entity\AnneeScolaire;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Service\GeneraleService;


class PunitionsAbsenceService
{
    private $em;
    private $container;
    private $service;
    private $GeneraleService;

    /**
     * GeneraleExtension constructor.
     * @param $em
     * @param $container
     */
    public function __construct(EntityManagerInterface $em, ContainerInterface $container, GeneraleService $GeneraleService)
    {
        $this->em = $em;
        $this->container = $container;
        $this->GeneraleService = $GeneraleService;
    }

    function getPunitionsAbsencesByClasse($classe){
        $expr = $this->em->getExpressionBuilder();
        $annee = $this->em->getRepository(AnneeScolaire::class)->findOneBy(['current' => true]);
        return $this->em->createQueryBuilder()
            ->select('pa')
            ->from(PunitionsAbsences::class, 'pa')
            ->where('pa.annee_scolaire = :annee')
            ->andWhere('pa.classe = :classe')
           // ->andWhere($expr->in('pa.type', [PunitionsAbsences::ABSENCE, PunitionsAbsences::EXCLUSION/*, PunitionsAbsences::RETARD*/]))
            ->andWhere($expr->in('pa.type', [PunitionsAbsences::ABSENCE, PunitionsAbsences::EXCLUSION]))
            ->andWhere('pa.dateImpression Is Null')
            ->andWhere('pa.modeReglement Is Null')
            ->setParameters([
                'annee' => $annee,
                'classe' => $classe
            ])
            ->getQuery()->getResult();
    }

    function getNotificationPunitionsAbsences(){
        $annee = $this->em->getRepository(AnneeScolaire::class)->findOneBy(['current' => true]);
        $absences_avertissements = $this->em->createQueryBuilder()
            ->select('pa')
            ->from(PunitionsAbsences::class, 'pa')
            ->where('pa.type = :typeAbs AND pa.dateDebut <= :minDate AND pa.dateFin Is Null')
            ->orWhere('pa.type = :typeAvert AND pa.punitions_absences Is Null')
            ->andWhere('pa.annee_scolaire = :annee')
            ->setParameters([
                'typeAbs' => PunitionsAbsences::ABSENCE,
                'typeAvert' => PunitionsAbsences::AVERTISSEMENT,
                'minDate' => (new \DateTime())->modify('-3 day'),
                'annee' => $annee
            ])
            ->getQuery()->getResult();

        $response = [];
        // $jourFerie = $this->container->get('App\Service\GeneraleService')->getJoursFeries($annee);
        $jourFerie = $this->GeneraleService->getJoursFeries($annee);

        /** @var PunitionsAbsences $absc_avert */
        foreach ($absences_avertissements as $absc_avert) {
            $response[$absc_avert->getEleve()->getId()]['eleve'] = $absc_avert->getEleve();
            $response[$absc_avert->getEleve()->getId()]['id'] = $absc_avert->getId();
            switch ($absc_avert->getType()) {
                case PunitionsAbsences::ABSENCE:
                    $nbrJ = $absc_avert->getNbrJoursAbsence($jourFerie);
                    if ($nbrJ >= 3)
                        $response[$absc_avert->getEleve()->getId()]['absence'] = $nbrJ;
                    break;
                case PunitionsAbsences::AVERTISSEMENT:
                    if (!isset($response[$absc_avert->getEleve()->getId()]['avertissement']))
                        $response[$absc_avert->getEleve()->getId()]['avertissement'] = [
                            'nbr' => 0,
                            'ids' => []
                        ];
                    $response[$absc_avert->getEleve()->getId()]['avertissement']['nbr'] += 1;
                    $response[$absc_avert->getEleve()->getId()]['avertissement']['ids'][] = $absc_avert->getId();
            }
        }

        foreach ($response as $key => $item)
            if (!isset($item['absence']) and (!isset($item['avertissement']) or $item['avertissement']['nbr'] < 3))
                unset($response[$key]);

        return $response;
    }
}