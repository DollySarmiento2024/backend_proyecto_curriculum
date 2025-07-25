<?php

namespace App\Entity;

use App\Repository\FormacionRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FormacionRepository::class)]
#[ORM\Table(name: 'formacion')]
class Formacion
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name:'id', type:Types::INTEGER)]
    private int $id;

    #[ORM\Column(name:'titulo', type:Types::STRING, length: 255)]
    private string $titulo;

    #[ORM\Column(name:'centro', type:Types::STRING, length: 150)]
    private string $centro;

    #[ORM\Column(name:'fecha_inicio', type:Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTime $fecha_inicio = null;

    #[ORM\Column(name:'fecha_fin', type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTime $fecha_fin = null;

    #[ORM\Column(name:'descripcion', type: Types::TEXT, nullable: true)]
    private ?string $descripcion = null;

    #[ORM\ManyToOne(targetEntity:Usuario::class, inversedBy: 'formaciones')]
    #[ORM\JoinColumn(name:'id_usuario', referencedColumnName: 'id')]
    private Usuario $usuario;

    public function getId(): int
    {
        return $this->id;
    }


    public function getTitulo(): string
    {
        return $this->titulo;
    }

    public function setTitulo(string $titulo): void
    {
        $this->titulo = $titulo;
    }

    public function getCentro(): string
    {
        return $this->centro;
    }

    public function setCentro(string $centro): void
    {
        $this->centro = $centro;
    }

    public function getFechaInicio(): ?\DateTime
    {
        return $this->fecha_inicio;
    }

    public function setFechaInicio(?\DateTime $fecha_inicio): void
    {
        $this->fecha_inicio = $fecha_inicio;
    }

    public function getFechaFin(): ?\DateTime
    {
        return $this->fecha_fin;
    }

    public function setFechaFin(?\DateTime $fecha_fin): void
    {
        $this->fecha_fin = $fecha_fin;
    }

    public function getDescripcion(): ?string
    {
        return $this->descripcion;
    }

    public function setDescripcion(?string $descripcion): void
    {
        $this->descripcion = $descripcion;
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
