<?php

namespace App\Repository;

use App\Entity\Curriculum;
use App\Entity\Usuario;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Curriculum>
 */
class CurriculumRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Curriculum::class);
    }

    public function save(Curriculum $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Curriculum $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);
        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function new(string $formacion, string $experiencia, string $habilidad, string $idioma, string $conocimiento, Usuario $usuario): int
    {
        $curriculum = new Curriculum();
        $curriculum->setFormacion($formacion);
        $curriculum->setExperiencia($experiencia);
        $curriculum->setHabilidad($habilidad);
        $curriculum->setIdioma($idioma);
        $curriculum->setConocimiento($conocimiento);
        $curriculum->setUsuario($usuario);
        $this->getEntityManager()->persist($curriculum);
        $this->getEntityManager()->flush();

        //devolvemos id creado
        return $curriculum->getId();
    }

}




    //    /**
    //     * @return Curriculum[] Returns an array of Curriculum objects
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

    //    public function findOneBySomeField($value): ?Curriculum
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

