<?php

namespace App\Repository;

use App\Entity\TypeRent;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TypeRent>
 *
 * @method TypeRent|null find($id, $lockMode = null, $lockVersion = null)
 * @method TypeRent|null findOneBy(array $criteria, array $orderBy = null)
 * @method TypeRent[]    findAll()
 * @method TypeRent[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TypeRentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TypeRent::class);
    }

//    /**
//     * @return TypeRent[] Returns an array of TypeRent objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('t.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?TypeRent
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
