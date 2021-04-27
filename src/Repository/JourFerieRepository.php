<?php

namespace App\Repository;

use App\Entity\JourFerie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method JourFerie|null find($id, $lockMode = null, $lockVersion = null)
 * @method JourFerie|null findOneBy(array $criteria, array $orderBy = null)
 * @method JourFerie[]    findAll()
 * @method JourFerie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class JourFerieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, JourFerie::class);
    }

   /**
     * @return JourFerie[] Returns an array of JourFerie objects
     */
     public function getListJourFerie($sort="id", $order="DESC", $id)
     {
         $query = $this->createQueryBuilder("jourferie") ->innerJoin('jourferie.user', 'u')
         ->andWhere('u.id = :id')
         ->setParameter('id', $id);
 
         return $query
            // ->where('jourferie.annee_scolaire = :annee')
            // ->setParameter('annee', $annee)
             ->orderBy("jourferie.$sort", $order)
             ->getQuery()
             ->getResult();
     }
 }