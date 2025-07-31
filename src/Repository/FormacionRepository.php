<?php

namespace App\Repository;
use App\Entity\Formacion;
use App\Entity\Usuario;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Formacion>
 */
class FormacionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Formacion::class);
    }

    public function save(Formacion $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);
        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Formacion $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);
        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function new(string $titulo, string $centro, \DateTime $fecha_inicio, \DateTime $fecha_fin, string $descripcion, Usuario $usuario): int
    {
        $formacion = new Formacion();
        $formacion->setTitulo($titulo);
        $formacion->setCentro($centro);
        $formacion->setFechaInicio($fecha_inicio);
        $formacion->setFechaFin($fecha_fin);
        $formacion->setDescripcion($descripcion);
        $formacion->setUsuario($usuario);
        $this->getEntityManager()->persist($formacion);
        $this->getEntityManager()->flush();

        //devolvemos id creado
        return $formacion->getId();
    }
}
    //    /**
    //     * @return Formacion[] Returns an array of Formacion objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('f')
    //            ->andWhere('f.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('f.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Formacion
    //    {
    //        return $this->createQueryBuilder('f')
    //            ->andWhere('f.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

