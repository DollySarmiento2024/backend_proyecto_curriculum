<?php

namespace App\Repository;
use App\Entity\Experiencia;
use App\Entity\Usuario;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Experiencia>
 */
class ExperienciaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Experiencia::class);
    }

    public function save(Experiencia $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);
        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Experiencia $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);
        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function new(string $puesto, string $empresa, \DateTime $fecha_inicio, \DateTime $fecha_fin, string $descripcion, Usuario $usuario): void
    {
        $experiencia = new Experiencia();
        $experiencia->setPuesto($puesto);
        $experiencia->setEmpresa($empresa);
        $experiencia->setFechaInicio($fecha_inicio);
        $experiencia->setFechaFin($fecha_fin);
        $experiencia->setDescripcion($descripcion);
        $experiencia->setUsuario($usuario);
        $this->getEntityManager()->persist($experiencia);
        $this->getEntityManager()->flush();
    }
}

    //    /**
    //     * @return Experiencia[] Returns an array of Experiencia objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('e')
    //            ->andWhere('e.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('e.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Experiencia
    //    {
    //        return $this->createQueryBuilder('e')
    //            ->andWhere('e.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

