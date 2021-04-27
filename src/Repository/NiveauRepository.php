<?php

namespace App\Repository;

use App\Entity\Niveau;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Niveau|null find($id, $lockMode = null, $lockVersion = null)
 * @method Niveau|null findOneBy(array $criteria, array $orderBy = null)
 * @method Niveau[]    findAll()
 * @method Niveau[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NiveauRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Niveau::class);
    }
/**
     * @return Niveau[] Returns an array of Niveau objects
     */

     public function getListNiveau($sort="id", $id, $order="DESC")
     {
         $query = $this->createQueryBuilder("niveau")->innerJoin('niveau.user', 'u')
         ->andWhere('u.id = :id')
         ->setParameter('id', $id);
         
         return $query
             ->orderBy("niveau.$sort", $order)
             ->getQuery()
             ->getResult();
     }
 
     public function getNiveauBycurentUser($id)
     {
         $query = $this->createQueryBuilder("niveau")
          ->innerJoin('niveau.user', 'u')
          ->andWhere('u.id = :id')
         // ->andWhere('niveau.code = :code')
 
         ->setParameter('id', $id)
         // ->setParameter('code', $code)
         ;
 
         return $query
             ->getQuery()
              ->getOneOrNullResult();
            // ->getResult();
 
     }
}
