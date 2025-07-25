<?php

namespace App\Repository;

use App\Entity\Curriculum;
use App\Entity\Recomendacion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Recomendacion>
 */
class RecomendacionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Recomendacion::class);
    }

    public function save(curriculum $entity, bool $flush = false): void{

        $this->getEntityManager()->persist($entity);
        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }


    public function remove(Curriculum $curriculum, bool $flush = false): void
    {
        $this->getEntityManager()->remove($curriculum);
        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function new(string $score, string $fecha): void
    {
        $recomendacion = new Curriculum();
        $recomendacion ->setScore($score);
        $recomendacion->setFecha($fecha);
        $this->getEntityManager()->persist($recomendacion);
        $this->getEntityManager()->flush();

    }
}

    //    /**
    //     * @return Recomendacion[] Returns an array of Recomendacion objects
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

    //    public function findOneBySomeField($value): ?Recomendacion
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

