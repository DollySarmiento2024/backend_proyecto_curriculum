<?php

namespace App\Entity;

use App\Repository\IdiomaRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: IdiomaRepository::class)]
#[ORM\Table(name: 'idioma')]
class Idioma
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name:'id', type:Types::INTEGER)]
    private int $id;

    #[ORM\Column(name:'nombre', type:Types::STRING, length: 100)]
    private string $nombre;

    #[ORM\Column(name:'nivel', type:Types::STRING, length: 100, nullable: true)]
    private ?string $nivel = null;

    #[ORM\ManyToOne(targetEntity:Usuario::class, inversedBy: 'idiomas')]
    #[ORM\JoinColumn(name:'id_usuario', referencedColumnName: 'id')]
    private Usuario $usuario;

    public function getId(): int
    {
        return $this->id;
    }


    public function getNombre(): string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): void
    {
        $this->nombre = $nombre;
    }



    public function getNivel(): ?string
    {
        return $this->nivel;
    }

    public function setNivel(?string $nivel): void
    {
        $this->nivel = $nivel;
    }

    public function getUsuario(): Usuario
    {
        return $this->usuario;
    }

    public function setUsuario(Usuario $usuario): void
    {
        $this->usuario = $usuario;
    }

}
