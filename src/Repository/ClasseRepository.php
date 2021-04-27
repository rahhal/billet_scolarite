<?php

namespace App\Repository;

use App\Entity\Classe;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Classe|null find($id, $lockMode = null, $lockVersion = null)
 * @method Classe|null findOneBy(array $criteria, array $orderBy = null)
 * @method Classe[]    findAll()
 * @method Classe[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ClasseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Classe::class);
    }

      /**
     * @return Classe[] Returns an array of Classe objects
     */
     public function getListClasse($sort="id", $id, $order="DESC")
     {
         $query = $this->createQueryBuilder("classe") ->innerJoin('classe.user', 'u')
             ->andWhere('u.id = :id')
             ->setParameter('id', $id);
 
         return $query
             ->orderBy("classe.$sort", $order)
             ->getQuery()
             ->getResult();
     }
     public function getClasseBycurentUser($id)
     {
         $query = $this->createQueryBuilder("c")
          ->innerJoin('c.user', 'u')
          ->where('u.id = :id')
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
