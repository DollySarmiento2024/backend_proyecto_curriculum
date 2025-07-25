<?php

namespace App\Entity;

use App\Repository\OfertaEmpleoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OfertaEmpleoRepository::class)]
#[ORM\Table(name: 'oferta_empleo')]
class OfertaEmpleo
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'id', type:Types::INTEGER)]
    private int $id;

    #[ORM\Column(name: 'titulo', type:Types::STRING, length: 255, unique: true)]
    private string $titulo;

    #[ORM\Column(name: 'descripcion', type:Types::TEXT)]
    private string $descripcion;

    #[ORM\Column(name: 'ubicacion', type:Types::STRING, length: 150, nullable: true)]
    private ?string $ubicacion = null;

    #[ORM\Column(name: 'tipo_contrato', type:Types::STRING, length: 100,  nullable: true)]
    private ?string $tipo_contrato = null;

    #[ORM\Column(name: 'salario', type:Types::STRING, length: 100, nullable: true)]
    private ?string $salario = null;

    #[ORM\Column(name: 'fecha_publicacion', type:Types::DATETIME_IMMUTABLE)]
    private \DateTimeImmutable $fecha_publicacion;

    #[ORM\ManyToOne(targetEntity:Empresa::class, inversedBy: 'ofertas_empleo')]
    #[ORM\JoinColumn(name:'id_empresa', referencedColumnName: 'id')]
    private Empresa $empresa;

    /**
     * @var Collection<int, Postulacion>
     */
    #[ORM\OneToMany(targetEntity: Postulacion::class, mappedBy: 'oferta_empleo',  cascade: ['persist', 'remove'], orphanRemoval: true)]
    private Collection $postulaciones;

    /**
     * @var Collection<int, Recomendacion>
     */
    #[ORM\OneToMany(targetEntity: Recomendacion::class, mappedBy: 'oferta_empleo', cascade: ['persist', 'remove'], orphanRemoval: true)]
    private Collection $recomendaciones;

    public function __construct()
    {
        $this->postulaciones = new ArrayCollection();
        $this->recomendaciones = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getTitulo(): string
    {
        return $this->titulo;
    }

    public function setTitulo(string $titulo): void
    {
        $this->titulo = $titulo;
    }

    public function getDescripcion(): string
    {
        return $this->descripcion;
    }

    public function setDescripcion(string $descripcion): void
    {
        $this->descripcion = $descripcion;
    }

    public function getUbicacion(): ?string
    {
        return $this->ubicacion;
    }

    public function setUbicacion(?string $ubicacion): void
    {
        $this->ubicacion = $ubicacion;
    }

    public function getTipoContrato(): ?string
    {
        return $this->tipo_contrato;
    }

    public function setTipoContrato(?string $tipo_contrato): void
    {
        $this->tipo_contrato = $tipo_contrato;
    }

    public function getSalario(): ?string
    {
        return $this->salario;
    }

    public function setSalario(?string $salario): void
    {
        $this->salario = $salario;
    }

    public function getFechaPublicacion(): \DateTimeImmutable
    {
        return $this->fecha_publicacion;
    }

    public function setFechaPublicacion(\DateTimeImmutable $fecha_publicacion): void
    {
        $this->fecha_publicacion = $fecha_publicacion;
    }

    public function getEmpresa(): Empresa
    {
        return $this->empresa;
    }

    public function setEmpresa(Empresa $empresa): void
    {
        $this->empresa = $empresa;
    }


    /**
     * @return Collection<int, Postulacion>
     */
    public function getPostulaciones(): Collection
    {
        return $this->postulaciones;
    }

    public function addPostulacion(Postulacion $postulacion): static
    {
        if (!$this->postulaciones->contains($postulacion)) {
            $this->postulaciones->add($postulacion);
            $postulacion->setOfertaEmpleo($this);
        }
        return $this;
    }

    public function removePostulacion(Postulacion $postulacion): static
    {
        if ($this->postulaciones->removeElement($postulacion)) {
            // set the owning side to null (unless already changed)
            if ($postulacion->getOfertaEmpleo() === $this) {
                $postulacion->setOfertaEmpleo(null);
            }
        }
        return $this;
    }

    /**
     * @return Collection<int, Recomendacion>
     */
    public function getRecomendaciones(): Collection
    {
        return $this->recomendaciones;
    }

    public function addRecomendacion(Recomendacion $recomendacion): static
    {
        if (!$this->recomendaciones->contains($recomendacion)) {
            $this->recomendaciones->add($recomendacion);
            $recomendacion->setOfertaEmpleo($this);
        }

        return $this;
    }

    public function removeRecomendacion(Recomendacion $recomendacion): static
    {
        if ($this->recomendaciones->removeElement($recomendacion)) {
            // set the owning side to null (unless already changed)
            if ($recomendacion->getOfertaEmpleo() === $this) {
                $recomendacion->setOfertaEmpleo(null);
            }
        }

        return $this;
    }
}
