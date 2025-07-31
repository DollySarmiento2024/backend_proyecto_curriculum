<?php

namespace App\Repository;
use App\Entity\Habilidad;
use App\Entity\Usuario;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Habilidad>
 */
class HabilidadRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Habilidad::class);
    }

    public function save(Habilidad $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);
        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Habilidad $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);
        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function new(string $nombre, string $nivel, string $descripcion, Usuario $usuario): int
    {
        $habilidad = new Habilidad();
        $habilidad->setNombre($nombre);
        $habilidad->setNivel($nivel);
        $habilidad->setDescripcion($descripcion);
        $habilidad->setUsuario($usuario);
        $this->getEntityManager()->persist($habilidad);
        $this->getEntityManager()->flush();

        //devolvemos id creado
        return $habilidad->getId();
    }
}

    //    /**
    //     * @return Habilidad[] Returns an array of Habilidad objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('h')
    //            ->andWhere('h.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('h.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Habilidad
    //    {
    //        return $this->createQueryBuilder('h')
    //            ->andWhere('h.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
