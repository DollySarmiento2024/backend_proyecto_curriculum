<?php

namespace App\Repository;

use App\Entity\Empresa;
use App\Entity\OfertaEmpleo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<OfertaEmpleo>
 */
class OfertaEmpleoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OfertaEmpleo::class);
    }

    public function save(OfertaEmpleo $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);
        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(OfertaEmpleo $ofertaEmpleo, bool $flush = false): void
    {
        $this->getEntityManager()->remove($ofertaEmpleo);
        if($flush){
            $this->getEntityManager()->flush();
        }
    }

    public function new(string $titulo, string $descripcion, string $ubicacion, string $tipo_contrato, string $salario, \DateTimeImmutable $fecha_publicacion, Empresa $empresa): void
    {
        $ofertaEmpleo = new OfertaEmpleo();
        $ofertaEmpleo->setTitulo($titulo);
        $ofertaEmpleo->setDescripcion($descripcion);
        $ofertaEmpleo->setUbicacion($ubicacion);
        $ofertaEmpleo->setTipoContrato($tipo_contrato);
        $ofertaEmpleo->setSalario($salario);
        $ofertaEmpleo->setFechaPublicacion($fecha_publicacion);
        $ofertaEmpleo->setEmpresa($empresa);
        $this->getEntityManager()->persist($ofertaEmpleo);
        $this->getEntityManager()->flush();
    }
}
    //    /**
    //     * @return OfertaEmpleo[] Returns an array of OfertaEmpleo objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('o')
    //            ->andWhere('o.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('o.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?OfertaEmpleo
    //    {
    //        return $this->createQueryBuilder('o')
    //            ->andWhere('o.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }



