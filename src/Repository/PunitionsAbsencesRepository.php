<?php

namespace App\Repository;

use App\Entity\PunitionsAbsences;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PunitionsAbsences|null find($id, $lockMode = null, $lockVersion = null)
 * @method PunitionsAbsences|null findOneBy(array $criteria, array $orderBy = null)
 * @method PunitionsAbsences[]    findAll()
 * @method PunitionsAbsences[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PunitionsAbsencesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PunitionsAbsences::class);
    }

    // /**
    //  * @return PunitionsAbsences[] Returns an array of PunitionsAbsences objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?PunitionsAbsences
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
