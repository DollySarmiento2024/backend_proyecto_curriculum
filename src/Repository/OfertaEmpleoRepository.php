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

    public function new(string $titulo, string $descripcion, string $ubicacion, string $tipo_contrato, string $salario, \DateTimeImmutable $fecha_publicacion, Empresa $empresa): int
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

        //devolvemos id creado
        return $ofertaEmpleo->getId();
    }

    public function findByFilter(array $filtros): array {

        $qb = $this->createQueryBuilder('o');

        // Cargo o empresa (título o descripción)
        if (!empty($filtros['filtro_oferta_empleo'])) {
            $qb->andWhere('o.titulo LIKE :texto OR o.descripcion LIKE :texto')
                ->setParameter('texto', '%' . $filtros['filtro_oferta_empleo'] . '%');
        }

        // Ubicación
        if (!empty($filtros['filtro_ubicacion'])) {
            $qb->andWhere('o.ubicacion LIKE :ubicacion')
                ->setParameter('ubicacion', '%' . $filtros['filtro_ubicacion'] . '%');
        }

        // Tipo de contrato (ignorar "todos")
        if (
            !empty($filtros['filtro_contrato']) &&
            $filtros['filtro_contrato'] !== 'contrato_todos'
        ) {
            $qb->andWhere('o.tipo_contrato = :contrato')
                ->setParameter('contrato', $filtros['filtro_contrato']);
        }

        // Fecha de publicación (desde)
        if (!empty($filtros['filtro_fecha'])) {
            $qb->andWhere('o.fecha_publicacion >= :fecha')
                ->setParameter('fecha', new \DateTimeImmutable($filtros['filtro_fecha']));
        }

        $query = $qb->orderBy('o.fecha_publicacion', 'DESC')->getQuery();
        return $query->execute();
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



