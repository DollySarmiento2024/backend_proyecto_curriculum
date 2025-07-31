<?php

namespace App\Repository;
use App\Entity\Empresa;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Empresa>
 */
class EmpresaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Empresa::class);
    }

    public function save(Empresa $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);
        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Empresa $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);
        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function new(string $nombre, string $email, string $telefono, string $direccion, string $ciudad, string $sector, string $descripcion, string $logo, string $sitio_web, string $redes_sociales): int
    {
        $empresa = new Empresa();
        $empresa->setNombre($nombre);
        $empresa->setEmail($email);
        $empresa->setTelefono($telefono);
        $empresa->setDireccion($direccion);
        $empresa->setCiudad($ciudad);
        $empresa->setSector($sector);
        $empresa->setDescripcion($descripcion);
        $empresa->setLogo($logo);
        $empresa->setSitioWeb($sitio_web);
        $empresa->setRedesSociales($redes_sociales);
        $this->getEntityManager()->persist($empresa);
        $this->getEntityManager()->flush();

        //devolvemos id creado
        return $empresa->getId();
    }

    //    /**
    //     * @return Empresa[] Returns an array of Empresa objects
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

    //    public function findOneBySomeField($value): ?Empresa
    //    {
    //        return $this->createQueryBuilder('e')
    //            ->andWhere('e.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

}
