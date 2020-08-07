<?php

namespace App\Repository;

use App\Entity\Publication;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Publication|null find($id, $lockMode = null, $lockVersion = null)
 * @method Publication|null findOneBy(array $criteria, array $orderBy = null)
 * @method Publication[]    findAll()
 * @method Publication[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PublicationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Publication::class);
    }

    // /**
    //  * @return Publication[] Returns an array of Publication objects
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
    public function findOneBySomeField($value): ?Publication
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
    public function findListByFilter($titre,$type,$ville):array
    {$qb=$this->createQueryBuilder('c');
        if ($titre!="")
        {
            $qb->orWhere('c.titre LIKE :titre')
                ->setParameter('titre','%'.$titre.'%');
        }
        if ($type!="")
        {
            $qb->orWhere('c.type LIKE :type')
                ->setParameter('type','%'.$type.'%');
        }
        if ($ville!="")
        {
            $qb->orWhere('c.ville LIKE :ville')
                ->setParameter('ville','%'.$ville.'%');
        }

        return $qb
            ->getQuery()
            ->getResult();

    }
}
