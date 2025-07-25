<?php

namespace App\Entity;

use App\Repository\CurriculumRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CurriculumRepository::class)]
#[ORM\Table(name: 'curriculum')]
class Curriculum
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'id', type:Types::TEXT)]
    private int $id;

    #[ORM\Column(name: 'formacion', type: Types::TEXT)]
    private string $formacion;

    #[ORM\Column(name: 'experiencia', type: Types::TEXT)]
    private string $experiencia;

    #[ORM\Column(name: 'habilidad', type: Types::TEXT)]
    private string $habilidad;

    #[ORM\Column(name: 'idioma', type: Types::TEXT)]
    private string $idioma;

    #[ORM\Column(name: 'conocimiento', type: Types::TEXT)]
    private string $conocimiento;

    #[ORM\OneToOne(targetEntity:Usuario::class, inversedBy: 'curriculum')]
    #[ORM\JoinColumn(name:'usuario_id', referencedColumnName:'id')]
    private Usuario $usuario;

    public function getId(): int
    {
        return $this->id;
    }

    public function getFormacion(): string
    {
        return $this->formacion;
    }

    public function setFormacion(string $formacion): void
    {
        $this->formacion = $formacion;
    }

    public function getExperiencia(): string
    {
        return $this->experiencia;
    }

    public function setExperiencia(string $experiencia): void
    {
        $this->experiencia = $experiencia;
    }

    public function getHabilidad(): string
    {
        return $this->habilidad;
    }

    public function setHabilidad(string $habilidad): void
    {
        $this->habilidad = $habilidad;
    }

    public function getIdioma(): string
    {
        return $this->idioma;
    }

    public function setIdioma(string $idioma): void
    {
        $this->idioma = $idioma;
    }

    public function getConocimiento(): string
    {
        return $this->conocimiento;
    }

    public function setConocimiento(string $conocimiento): void
    {
        $this->conocimiento = $conocimiento;
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
