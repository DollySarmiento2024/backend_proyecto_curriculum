<?php

namespace App\Repository;
use App\Entity\Conocimiento;
use App\Entity\Usuario;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Conocimiento>
 */
class ConocimientoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Conocimiento::class);
    }

    public function save(Conocimiento $entity, bool $flush = true): void
    {
        $this->getEntityManager()->persist($entity);
        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Conocimiento $entity, bool $flush = true): void
    {
        $this->getEntityManager()->remove($entity);
        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function new(string $nombre, string $nivel, string $descripcion, Usuario $usuario): int
    {
        $conocimiento = new Conocimiento();
        $conocimiento->setNombre($nombre);
        $conocimiento->setNivel($nivel);
        $conocimiento->setDescripcion($descripcion);
        $conocimiento->setUsuario($usuario);
        $this->getEntityManager()->persist($conocimiento);
        $this->getEntityManager()->flush();

        //devolvemos id creado
        return $conocimiento->getId();
    }
}
    //    /**
    //     * @return Conocimiento[] Returns an array of Conocimiento objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('c.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Conocimiento
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }


