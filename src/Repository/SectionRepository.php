<?php

namespace App\Repository;

use App\Entity\Section;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Section|null find($id, $lockMode = null, $lockVersion = null)
 * @method Section|null findOneBy(array $criteria, array $orderBy = null)
 * @method Section[]    findAll()
 * @method Section[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SectionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Section::class);
    }

   
    /**
     * @return Section[] Returns an array of Section objects
     */
     public function getListSection($sort="id", $id, $order="DESC")
     {
         $query = $this->createQueryBuilder("section")->innerJoin('section.user', 'u')
         ->andWhere('u.id = :id')
         ->setParameter('id', $id);
 
 
         return $query
             ->orderBy("section.$sort", $order)
             ->getQuery()
             ->getResult();
     }
     public function getSectionBycurentUser($id)
     {
         $query = $this->createQueryBuilder("section")
          ->leftJoin('section.user', 'u')
          ->andWhere('u.id = :id')
         // ->andWhere('section.code = :code')
 
         ->setParameter('id', $id)
         // ->setParameter('code', $code)
         ;
 
         return $query
             ->getQuery()
              ->getOneOrNullResult();
            // ->getResult();
 
     }
 }
 