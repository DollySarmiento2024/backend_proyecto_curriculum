<?php

namespace App\Entity;

use App\Repository\RecomendacionRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RecomendacionRepository::class)]
#[ORM\Table(name: 'recomendacion')]
class Recomendacion
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'id', type:Types::INTEGER)]
    private int $id;

    #[ORM\Column(name: 'score', type: Types::DECIMAL, precision: 4, scale: 1)]
    private float $score;

    #[ORM\Column(name:'fecha', type: Types::DATETIME_MUTABLE)]
    private \DateTime $fecha;

    #[ORM\ManyToOne(targetEntity:Usuario::class, inversedBy: 'recomendaciones')]
    #[ORM\JoinColumn(name:'id_usuario', referencedColumnName:'id')]
    private Usuario $usuario;

    #[ORM\ManyToOne(targetEntity:OfertaEmpleo::class,inversedBy: 'recomendaciones')]
    #[ORM\JoinColumn(name:'id_oferta_empleo', referencedColumnName:'id')]
    private OfertaEmpleo $oferta_empleo;

    public function getId(): int
    {
        return $this->id;
    }

    public function getScore(): string
    {
        return $this->score;
    }

    public function setScore(float $score): void
    {
        $this->score = $score;
    }

    public function getFecha(): \DateTime
    {
        return $this->fecha;
    }

    public function setFecha(\DateTime $fecha): void
    {
        $this->fecha = $fecha;
    }

    public function getUsuario(): Usuario
    {
        return $this->usuario;
    }

    public function setUsuario(Usuario $usuario): void
    {
        $this->usuario = $usuario;
    }

    public function getOfertaEmpleo(): OfertaEmpleo
    {
        return $this->oferta_empleo;
    }

    public function setOfertaEmpleo(OfertaEmpleo $oferta_empleo): void
    {
        $this->oferta_empleo = $oferta_empleo;
    }




}
