<?php

namespace App\Repository;

use App\Entity\Matiere;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Matiere|null find($id, $lockMode = null, $lockVersion = null)
 * @method Matiere|null findOneBy(array $criteria, array $orderBy = null)
 * @method Matiere[]    findAll()
 * @method Matiere[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MatiereRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Matiere::class);
    }
 /**
     * @return Matiere[] Returns an array of Matiere objects
     */
     public function getListMatiere($sort="id", $id, $order="DESC")
     {
         $query = $this->createQueryBuilder("matiere")->innerJoin('matiere.user', 'u')
         ->andWhere('u.id = :id')
         ->setParameter('id', $id);
 
         return $query
             ->orderBy("matiere.$sort", $order)
             ->getQuery()
             ->getResult();
     }
     public function getMatiereBycurentUser($id)
     {
         $query = $this->createQueryBuilder("matiere")
          ->innerJoin('matiere.user', 'u')
          ->andWhere('u.id = :id')
         // ->andWhere('matiere.code = :code')
 
          ->setParameter('id', $id)
         // ->setParameter('code', $code)
         ;
 
         return $query
             ->getQuery()
              ->getOneOrNullResult();
            // ->getResult();
 
     }
 }
 