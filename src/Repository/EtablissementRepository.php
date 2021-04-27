<?php

namespace App\Repository;

use App\Entity\Etablissement;
use App\Entity\Search\EtablissementSearch;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Etablissement|null find($id, $lockMode = null, $lockVersion = null)
 * @method Etablissement|null findOneBy(array $criteria, array $orderBy = null)
 * @method Etablissement[]    findAll()
 * @method Etablissement[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EtablissementRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Etablissement::class);
    }

    // /**
    //  * @return Etablissement[] Returns an array of Etablissement objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Etablissement
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
     /**
     * @return Etablissement[] Returns an array of Etablissement objects
     */
     public function getListEtablissement(EtablissementSearch $search, $id, $sort="id", $order="DESC")
     {
         $query = $this->createQueryBuilder("etablissement")
         ->innerJoin('etablissement.user', 'u')
         ->andWhere('u.id = :id')
         ->setParameter('id', $id);
 
         if ($search->getMinistere())
             $query->andWhere($query->expr()->like("UPPER(etablissement.ministere)", "UPPER('%".$search->getMinistere()."%')"));
 
         if ($search->getMandoubia())
             $query->andWhere($query->expr()->like("UPPER(etablissement.mandoubia)", "UPPER('%".$search->getMandoubia()."%')"));
 
         if ($search->getCode())
             $query->andWhere($query->expr()->like("UPPER(etablissement.code)", "UPPER('%".$search->getCode()."%')"));
 
         if ($search->getNom())
             $query->andWhere($query->expr()->like("UPPER(etablissement.nom)", "UPPER('%".$search->getNom()."%')"));
 
         if ($search->getGouvernorat())
             $query->andWhere("etablissement.gouvernorat = :gouvernorat")
                 ->setParameter("gouvernorat", $search->getGouvernorat());
 
         if ($search->getVille())
             $query->andWhere($query->expr()->like("UPPER(etablissement.ville)", "UPPER('%".$search->getVille()."%')"));
 
         if ($search->getAdresse())
             $query->andWhere($query->expr()->like("UPPER(etablissement.adresse)", "UPPER('%".$search->getAdresse()."%')"));
 
         if ($search->getTel())
             $query->andWhere($query->expr()->like("UPPER(etablissement.tel)", "UPPER('%".$search->getTel()."%')"));
 
         if ($search->getFax())
             $query->andWhere($query->expr()->like("UPPER(etablissement.fax)", "UPPER('%".$search->getFax()."%')"));
 
         return $query
             ->orderBy("etablissement.$sort", $order)
             ->getQuery()
             ->getResult();
     }
}
