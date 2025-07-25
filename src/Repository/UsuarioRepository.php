<?php

namespace App\Repository;

use App\Entity\Usuario;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Usuario>
 */
class UsuarioRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Usuario::class);
    }

    public function save(Usuario $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if($flush){
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Usuario $usuario, bool $flush = false): void
    {
        $this->getEntityManager()->remove($usuario);
        if($flush){
            $this->getEntityManager()->flush();
        }
    }

    public function new(string $nombre, string $apellidos, string $email, string $telefono, string $direccion, string $redes_sociales, string $foto, string $resumen_perfil): void
    {
        $usuario = new Usuario();
        $usuario->setNombre($nombre);
        $usuario->setApellidos($apellidos);
        $usuario->setEmail($email);
        $usuario->setTelefono($telefono);
        $usuario->setDireccion($direccion);
        $usuario->setRedesSociales($redes_sociales);
        $usuario->setFoto($foto);
        $usuario->setResumenPerfil($resumen_perfil);
        $this->getEntityManager()->persist($usuario);
        $this->getEntityManager()->flush();
    }
}
