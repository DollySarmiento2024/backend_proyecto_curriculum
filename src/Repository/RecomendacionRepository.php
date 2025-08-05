<?php

namespace App\Repository;

use App\Entity\Curriculum;
use App\Entity\OfertaEmpleo;
use App\Entity\Recomendacion;
use App\Entity\Usuario;
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

    public function save(recomendacion $entity, bool $flush = false): void{

        $this->getEntityManager()->persist($entity);
        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }


    public function remove(Recomendacion $recomendacion, bool $flush = false): void
    {
        $this->getEntityManager()->remove($recomendacion);
        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function new(float $score, \DateTime $fecha, Usuario $usuario, OfertaEmpleo $oferta_empleo): int
    {
        $recomendacion = new Recomendacion();
        $recomendacion ->setScore($score);
        $recomendacion->setFecha($fecha);
        $recomendacion->setUsuario($usuario);
        $recomendacion->setOfertaEmpleo($oferta_empleo);
        $this->getEntityManager()->persist($recomendacion);
        $this->getEntityManager()->flush();

        //devolvemos id creado
        return $recomendacion->getId();
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

