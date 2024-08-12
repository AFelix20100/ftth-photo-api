<?php

namespace App\Repository;

use App\Entity\Photo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Photo>
 */
class PhotoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Photo::class);
    }

    //    /**
    //     * @return Photo[] Returns an array of Photo objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('p.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Photo
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

    public function findByReferencePrestation(string $referencePrestation)
    {
        return $this->createQueryBuilder('p')
            ->join('p.commande', 'c')
            ->where('c.referencePrestation = :referencePrestation')
            ->setParameter('referencePrestation', $referencePrestation)
            ->getQuery()
            ->getResult();
    }

    public function findByReferenceCommandeInterne(string $referenceCommandeInterne)
    {
        return $this->createQueryBuilder('p')
            ->join('p.commande', 'c')
            ->where('c.referenceCommandeInterne = :referenceCommandeInterne')
            ->setParameter('referenceCommandeInterne', $referenceCommandeInterne)
            ->getQuery()
            ->getResult();
    }
}
