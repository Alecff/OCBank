<?php

namespace App\Repository;

use App\Entity\CPU;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CPU|null find($id, $lockMode = null, $lockVersion = null)
 * @method CPU|null findOneBy(array $criteria, array $orderBy = null)
 * @method CPU[]    findAll()
 * @method CPU[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CPURepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CPU::class);
    }

    public function findAllContaining(string $query)
    {
         return $this->createQueryBuilder('c')
             ->andWhere('c.Name LIKE :query')
             ->setParameter('query', '%'.$query.'%')
             ->getQuery()
             ->getResult();
    }
}
