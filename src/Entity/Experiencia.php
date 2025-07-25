<?php

namespace App\Entity;

use App\Repository\ExperienciaRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ExperienciaRepository::class)]
#[ORM\Table(name: 'experiencia')]
class Experiencia
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name:'id', type:Types::INTEGER)]
    private int $id;

    #[ORM\Column(name:'puesto', type:Types::STRING, length: 255)]
    private string $puesto;

    #[ORM\Column(name:'empresa', type:Types::STRING, length: 255)]
    private string $empresa;

    #[ORM\Column(name:'fecha_inicio', type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTime $fecha_inicio = null;

    #[ORM\Column(name:'fecha_fin', type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTime $fecha_fin = null;

    #[ORM\Column(name:'descripcion', type: Types::TEXT, nullable: true)]
    private ?string $descripcion = null;

    #[ORM\ManyToOne(targetEntity:Usuario::class, inversedBy: 'experiencias')]
    #[ORM\JoinColumn(name:'id_usuario', referencedColumnName: 'id')]
    private Usuario $usuario;

    public function getId(): int
    {
        return $this->id;
    }


    public function getPuesto(): string
    {
        return $this->puesto;
    }

    public function setPuesto(string $puesto): void
    {
        $this->puesto = $puesto;
    }

    public function getEmpresa(): string
    {
        return $this->empresa;
    }

    public function setEmpresa(string $empresa): void
    {
        $this->empresa = $empresa;
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
