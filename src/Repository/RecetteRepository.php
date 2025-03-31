<?php

namespace App\Repository;

use App\Entity\Recette;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Recette>
 */
class RecetteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Recette::class);
    }

    public function findByIdUser(int $userId)
    {
        return $this->createQueryBuilder('r')
            ->innerJoin('r.user', 'p')
            ->addselect('p')
            ->where('p.id = :userId')
            ->setParameter('userId', $userId)
            ->getQuery()
            ->getResult();
    }

    public function findMessageByRecette(int $id)
    {
        return $this->createQueryBuilder('r')
            ->leftJoin('r.message', 'm')
            ->addselect('m')
            ->where('r.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getResult();
    }

    //    /**
    //     * @return Recette[] Returns an array of Recette objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('r.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Recette
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
