<?php

namespace App\Repository;

use App\Entity\Usuario;
use App\Entity\OfertaEmpleo;

use App\Entity\Postulacion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Postulacion>
 */
class PostulacionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Postulacion::class);
    }

    public function save(Postulacion $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);
        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Postulacion $postulacion, bool $flush = false): void
    {
        $this->getEntityManager()->remove($postulacion);
        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function new(\DateTimeImmutable $fecha, string $carta_presentacion, string $estado, float $score, Usuario $usuario, OfertaEmpleo $oferta_empleo): int
    {
        $postulacion = new Postulacion();
        $postulacion->setFecha( $fecha);
        $postulacion->setCartaPresentacion($carta_presentacion);
        $postulacion->setEstado($estado);
        $postulacion->setScore($score);
        $postulacion->setUsuario($usuario);
        $postulacion->setOfertaEmpleo($oferta_empleo);
        $this->getEntityManager()->persist($postulacion);
        $this->getEntityManager()->flush();

        //devolvemos id creado
        return $postulacion->getId();
    }

}

    //    /**
    //     * @return Postulacion[] Returns an array of Postulacion objects
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

    //    public function findOneBySomeField($value): ?Postulacion
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

