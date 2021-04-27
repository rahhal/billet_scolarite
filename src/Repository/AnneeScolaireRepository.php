<?php

namespace App\Repository;

use App\Entity\AnneeScolaire;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method AnneeScolaire|null find($id, $lockMode = null, $lockVersion = null)
 * @method AnneeScolaire|null findOneBy(array $criteria, array $orderBy = null)
 * @method AnneeScolaire[]    findAll()
 * @method AnneeScolaire[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AnneeScolaireRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AnneeScolaire::class);
    }

    // /**
    //  * @return AnneeScolaire[] Returns an array of AnneeScolaire objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?AnneeScolaire
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
    public function getListAnneeScolaire($sort="id", $id, $order="DESC")
    {
        $query = $this->createQueryBuilder("a")->innerJoin('a.user', 'u')
        ->andWhere('u.id = :id')
        ->setParameter('id', $id);


        return $query
            ->orderBy("a.$sort", $order)
            ->getQuery()
            ->getResult();
    }
}
