<?php

namespace App\Repository;

use App\Entity\Idioma;
use App\Entity\Usuario;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Idioma>
 */
class IdiomaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Idioma::class);
    }

    public function save(Idioma $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);
        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Idioma $idioma, bool $flush = false): void
    {
        $this->getEntityManager()->remove($idioma);
        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function new(string $nombre, string $nivel, Usuario $usuario): int
    {
        $idioma = new Idioma();
        $idioma->setNombre($nombre);
        $idioma->setNivel($nivel);
        $idioma->setUsuario($usuario);
        $this->getEntityManager()->persist($idioma);
        $this->getEntityManager()->flush();

        //devolvemos id creado
        return $idioma->getId();
    }
}

    //    /**
    //     * @return Idioma[] Returns an array of Idioma objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('i')
    //            ->andWhere('i.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('i.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Idioma
    //    {
    //        return $this->createQueryBuilder('i')
    //            ->andWhere('i.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

