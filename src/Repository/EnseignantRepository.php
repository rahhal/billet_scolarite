<?php

namespace App\Repository;

use App\Entity\Enseignant;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Enseignant|null find($id, $lockMode = null, $lockVersion = null)
 * @method Enseignant|null findOneBy(array $criteria, array $orderBy = null)
 * @method Enseignant[]    findAll()
 * @method Enseignant[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EnseignantRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Enseignant::class);
    }

   /**
     * @return Enseignant[] Returns an array of Enseignant objects
     */
     public function getListEnseignant($sort="id", $id, $order="DESC")
     {
         $query = $this->createQueryBuilder("enseignant")->innerJoin('enseignant.user', 'u')
         ->andWhere('u.id = :id')
         ->setParameter('id', $id);
 
 
         return $query
             ->orderBy("enseignant.$sort", $order)
             ->getQuery()
             ->getResult();
     }
 
     public function getEnseignantBycurentUser($id)
     {
         $query = $this->createQueryBuilder("enseignant")
          ->innerJoin('enseignant.user', 'u')
          ->andWhere('u.id = :id')
         // ->andWhere('enseignant.code = :code')
 
          ->setParameter('id', $id)
         // ->setParameter('code', $code)
         ;
 
 
         return $query
             ->getQuery()
              ->getOneOrNullResult();
            // ->getResult();
 
     }
 }
 