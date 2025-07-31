<?php

namespace App\Entity;

use App\Repository\PostulacionRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PostulacionRepository::class)]
#[ORM\Table(name:'postulacion')]
class Postulacion
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name:'id', type:Types::INTEGER)]
    private int $id;

    #[ORM\Column(name:'fecha', type: Types::DATE_IMMUTABLE)]
    private \DateTimeImmutable $fecha;

    #[ORM\Column(name:'carta_presentacion', type: Types::TEXT, nullable: true)]
    private ?string $carta_presentacion = null;

    //estados posibles: pendiente, aceptada, rechazada
    #[ORM\Column(name:'estado', type: Types::STRING, length: 50, options: ["default" => "pendiente"])]
    private string $estado = 'pendiente';

    #[ORM\Column(name: 'score', type: Types::DECIMAL, precision: 4, scale: 1)]
    private float $score;

    #[ORM\ManyToOne(targetEntity:Usuario::class, inversedBy: 'postulaciones')]
    #[ORM\JoinColumn(name:'id_usuario', referencedColumnName:'id')]
    private Usuario $usuario;

    #[ORM\ManyToOne(targetEntity:OfertaEmpleo::class, inversedBy: 'postulaciones')]
    #[ORM\JoinColumn(name:'id_oferta_empleo', referencedColumnName:'id')]
    private OfertaEmpleo $oferta_empleo;

    public function getId(): int
    {
        return $this->id;
    }

    public function getFecha(): \DateTimeImmutable
    {
        return $this->fecha;
    }

    public function setFecha(\DateTimeImmutable $fecha): void
    {
        $this->fecha = $fecha;
    }

    public function getCartaPresentacion(): ?string
    {
        return $this->carta_presentacion;
    }

    public function setCartaPresentacion(?string $carta_presentacion): void
    {
        $this->carta_presentacion = $carta_presentacion;
    }

    public function getEstado(): string
    {
        return $this->estado;
    }

    public function setEstado(string $estado): void
    {
        $this->estado = $estado;
    }

    public function getScore(): float
    {
        return $this->score;
    }

    public function setScore(float $score): void
    {
        $this->score = $score;
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
