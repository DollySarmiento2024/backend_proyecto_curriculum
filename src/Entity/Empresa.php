<?php

namespace App\Entity;
use App\Repository\EmpresaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EmpresaRepository::class)]
#[ORM\Table(name: 'empresa')]
class Empresa
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'id', type:Types::INTEGER)]
    private int $id;

    #[ORM\Column(name: 'nombre', type:Types::STRING, length: 255)]
    private string $nombre;

    #[ORM\Column(name: 'email', type:Types::STRING, length: 100)]
    private string $email;

    #[ORM\Column(name: 'telefono', type:Types::STRING, length: 20, nullable: true)]
    private string $telefono;

    #[ORM\Column(name: 'direccion', type:Types::STRING, length: 255, nullable: true)]
    private ?string $direccion = null;

    #[ORM\Column(name: 'ciudad', type:Types::STRING, length: 100, nullable: true)]
    private ?string $ciudad = null;

    #[ORM\Column(name: 'sector', type:Types::STRING, length: 100, nullable: true)]
    private ?string $sector = null;

    #[ORM\Column(name: 'descripcion', type:Types::TEXT, nullable: true)]
    private ?string $descripcion = null;

    #[ORM\Column(name: 'logo', type:Types::STRING, length: 100, nullable: true)]
    private ?string $logo = null;

    #[ORM\Column(name: 'sitio_web', type:Types::STRING, length: 255, nullable: true)]
    private ?string $sitio_web = null;

    #[ORM\Column(name: 'redes_sociales', type:Types::STRING, length: 255, nullable: true)]
    private ?string $redes_sociales = null;

    //RELACIONES
    /**
     * @var Collection<int, OfertaEmpleo>
     */
    #[ORM\OneToMany(targetEntity: OfertaEmpleo::class, mappedBy: 'empresa', cascade: ['persist', 'remove'], orphanRemoval: true)]
    private Collection $ofertas_empleo;

    public function __construct()
    {
        $this->ofertas_empleo = new ArrayCollection();
    }

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

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getTelefono(): string
    {
        return $this->telefono;
    }

    public function setTelefono(string $telefono): void
    {
        $this->telefono = $telefono;
    }

    public function getDireccion(): ?string
    {
        return $this->direccion;
    }

    public function setDireccion(?string $direccion): void
    {
        $this->direccion = $direccion;
    }

    public function getCiudad(): ?string
    {
        return $this->ciudad;
    }

    public function setCiudad(?string $ciudad): void
    {
        $this->ciudad = $ciudad;
    }


    public function getSector(): ?string
    {
        return $this->sector;
    }

    public function setSector(?string $sector): void
    {
        $this->sector = $sector;
    }

    public function getDescripcion(): ?string
    {
        return $this->descripcion;
    }

    public function setDescripcion(?string $descripcion): void
    {
        $this->descripcion = $descripcion;
    }

    public function getLogo(): ?string
    {
        return $this->logo;
    }

    public function setLogo(?string $logo): void
    {
        $this->logo = $logo;
    }

    public function getSitioWeb(): ?string
    {
        return $this->sitio_web;
    }

    public function setSitioWeb(?string $sitio_web): void
    {
        $this->sitio_web = $sitio_web;
    }

    public function getRedesSociales(): ?string
    {
        return $this->redes_sociales;
    }

    public function setRedesSociales(?string $redes_sociales): void
    {
        $this->redes_sociales = $redes_sociales;
    }


    /**
     * @return Collection<int, OfertaEmpleo>
     */
    public function getOfertasEmpleo(): Collection
    {
        return $this->ofertas_empleo;
    }

    public function addOfertaEmpleo(OfertaEmpleo $oferta_empleo): static
    {
        if (!$this->ofertas_empleo->contains($oferta_empleo)) {
            $this->ofertas_empleo->add($oferta_empleo);
            $oferta_empleo->setEmpresa($this);
        }
        return $this;
    }

    public function removeOfertaEmpleo(OfertaEmpleo $oferta_empleo): static
    {
        if ($this->ofertas_empleo->removeElement($oferta_empleo)) {
            // set the owning side to null (unless already changed)
            if ($oferta_empleo->getEmpresa() === $this) {
                $oferta_empleo->setEmpresa(null);
            }
        }
        return $this;
    }
}
