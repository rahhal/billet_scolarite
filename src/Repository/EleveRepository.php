<?php

namespace App\Repository;

use App\Entity\Eleve;
use App\Entity\Search\EleveSearch;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Eleve|null find($id, $lockMode = null, $lockVersion = null)
 * @method Eleve|null findOneBy(array $criteria, array $orderBy = null)
 * @method Eleve[]    findAll()
 * @method Eleve[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EleveRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Eleve::class);
    }

     /**
     * @return Eleve[] Returns an array of Eleve objects
     */
     public function getListEleve(EleveSearch $search, $id, $sort="id",$order="DESC")
     {
         $query = $this->createQueryBuilder("eleve")
             ->innerJoin('eleve.user', 'u')
             ->andWhere('u.id = :id')
             ->setParameter('id', $id);
 
 
         if ($search->getIdentifiant())
             $query->andWhere($query->expr()->like("UPPER(eleve.identifiant)", "UPPER('%".$search->getIdentifiant()."%')"));
 
         if ($search->getNomprenom())
             $query->andWhere($query->expr()->like("UPPER(eleve.nomprenom)", "UPPER('%".$search->getNomprenom()."%')"));
 
         if ($search->getEtablissementAnneeDerniere())
             $query->andWhere("eleve.etablissementAnneeDerniere = :etablissementAnneeDerniere")
                 ->setParameter("etablissementAnneeDerniere", $search->getEtablissementAnneeDerniere());
 
         if ($search->getClasseAnneeDerniere())
             $query->andWhere("eleve.classe_annee_derniere = :classeAnneeDerniere")
                 ->setParameter("classeAnneeDerniere", $search->getClasseAnneeDerniere());
 
         if ($search->getClasseAnneeActuelle())
             $query->andWhere("eleve.classeAnneeActuelle = :classeAnneeActuelle")
                 ->setParameter("classeAnneeActuelle", $search->getClasseAnneeActuelle());
 
         return $query
             ->orderBy("eleve.$sort", $order)
             ->getQuery()
             ->getResult();
     }
     public function getEleveBycurentUser($id)
     {
         $query = $this->createQueryBuilder("eleve")
          ->innerJoin('eleve.user', 'u')
          ->andWhere('u.id = :id')
         // ->andWhere('eleve.identifiant = :identifiant')
 
         ->setParameter('id', $id)
         // ->setParameter('identifiant', $identifiant)
         ;
 
         return $query
             ->getQuery()
              ->getOneOrNullResult();
            // ->getResult();
 
     }
 }
 