<?php

namespace App\Entity;
use App\Repository\UsuarioRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;


#[ORM\Entity(repositoryClass: UsuarioRepository::class)]
#[ORM\Table(name: 'usuario')]
class Usuario
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'id', type:Types::INTEGER)]
    private int $id;

    #[ORM\Column(name: 'nombre', type:Types::STRING, length: 100)]
    private string $nombre;

    #[ORM\Column(name: 'apellidos', type:Types::STRING, length: 100)]
    private string $apellidos;

    #[ORM\Column(name: 'email', type:Types::STRING, length: 100, unique: true)]
    private string $email;

    #[ORM\Column(name: 'telefono', type:Types::STRING, length: 20, nullable: true)]
    private ?string $telefono = null;

    #[ORM\Column(name: 'direccion', type:Types::STRING, length: 255, nullable: true)]
    private ?string $direccion = null;

    #[ORM\Column(name: 'ciudad', type:Types::STRING, length: 100, nullable: true)]
    private ?string $ciudad = null;

    #[ORM\Column(name: 'redes_sociales', type:Types::STRING, length: 255, nullable: true)]
    private ?string $redes_sociales = null;

    #[ORM\Column(name: 'foto', type:Types::STRING, length: 100, nullable: true)]
    private ?string $foto = null;

    #[ORM\Column(name: 'resumen_perfil', type: Types::TEXT, nullable: true)]
    private ?string $resumen_perfil = null;


    //RELACIONES
    /**
     * @var Collection<int, Formacion>
     */
    #[ORM\OneToMany(targetEntity: Formacion::class, mappedBy: 'usuario', cascade: ['persist', 'remove'], orphanRemoval: true)]
    private Collection $formaciones;

    /**
     * @var Collection<int, Experiencia>
     */
    #[ORM\OneToMany(targetEntity: Experiencia::class, mappedBy: 'usuario', cascade: ['persist', 'remove'], orphanRemoval: true)]
    private Collection $experiencias;

    /**
     * @var Collection<int, Habilidad>
     */
    #[ORM\OneToMany(targetEntity: Habilidad::class, mappedBy: 'usuario', cascade: ['persist', 'remove'], orphanRemoval: true)]
    private Collection $habilidades;

    /**
     * @var Collection<int, Idioma>
     */
    #[ORM\OneToMany(targetEntity: Idioma::class, mappedBy: 'usuario', cascade: ['persist', 'remove'], orphanRemoval: true)]
    private Collection $idiomas;

    /**
     * @var Collection<int, Conocimiento>
     */
    #[ORM\OneToMany(targetEntity: Conocimiento::class, mappedBy: 'usuario', cascade: ['persist', 'remove'], orphanRemoval: true)]
    private Collection $conocimientos;

    /**
     * @var Collection<int, Postulacion>
     */
    #[ORM\OneToMany(targetEntity: Postulacion::class, mappedBy: 'usuario', cascade: ['persist', 'remove'], orphanRemoval: true)]
    private Collection $postulaciones;

    /**
     * @var Collection<int, Recomendacion>
     */
    #[ORM\OneToMany(targetEntity: Recomendacion::class, mappedBy: 'usuario', cascade: ['persist', 'remove'], orphanRemoval: true)]
    private Collection $recomendaciones;

    #[ORM\OneToOne(targetEntity: Curriculum::class, mappedBy: 'usuario', cascade: ['persist', 'remove'], orphanRemoval: true)]
    private Curriculum $curriculum;

    /*#[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private Account $account;*/

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: true)]
    private ?Account $account = null;


    public function __construct()
    {
        $this->formaciones = new ArrayCollection();
        $this->experiencias = new ArrayCollection();
        $this->habilidades = new ArrayCollection();
        $this->idiomas = new ArrayCollection();
        $this->conocimientos = new ArrayCollection();
        $this->postulaciones = new ArrayCollection();
        $this->recomendaciones = new ArrayCollection();
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

    public function getApellidos(): string
    {
        return $this->apellidos;
    }

    public function setApellidos(string $apellidos): void
    {
        $this->apellidos = $apellidos;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getTelefono(): ?string
    {
        return $this->telefono;
    }

    public function setTelefono(?string $telefono): void
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


    public function getRedesSociales(): ?string
    {
        return $this->redes_sociales;
    }

    public function setRedesSociales(?string $redes_sociales): void
    {
        $this->redes_sociales = $redes_sociales;
    }

    public function getFoto(): ?string
    {
        return $this->foto;
    }

    public function setFoto(?string $foto): void
    {
        $this->foto = $foto;
    }

    public function getResumenPerfil(): ?string
    {
        return $this->resumen_perfil;
    }

    public function setResumenPerfil(?string $resumen_perfil): void
    {
        $this->resumen_perfil = $resumen_perfil;
    }




    /**
     * @return Collection<int, Formacion>
     */
    public function getFormaciones(): Collection
    {
        return $this->formaciones;
    }

    public function addFormacion(Formacion $formacion): static
    {
        if (!$this->formaciones->contains($formacion)) {
            $this->formaciones->add($formacion);
            $formacion->setUsuario($this);
        }

        return $this;
    }

    public function removeFormacion(Formacion $formacion): static
    {
        if ($this->formaciones->removeElement($formacion)) {
            // set the owning side to null (unless already changed)
            if ($formacion->getUsuario() === $this) {
                $formacion->setUsuario(null);
            }
        }
        return $this;
    }

    /**
     * @return Collection<int, Experiencia>
     */
    public function getExperiencias(): Collection
    {
        return $this->experiencias;
    }

    public function addExperiencia(Experiencia $experiencia): static
    {
        if (!$this->experiencias->contains($experiencia)) {
            $this->experiencias->add($experiencia);
            $experiencia->setUsuario($this);
        }
        return $this;
    }

    public function removeExperiencia(Experiencia $experiencia): static
    {
        if ($this->experiencias->removeElement($experiencia)) {
            // set the owning side to null (unless already changed)
            if ($experiencia->getUsuario() === $this) {
                $experiencia->setUsuario(null);
            }
        }
        return $this;
    }

    /**
     * @return Collection<int, Habilidad>
     */
    public function getHabilidades(): Collection
    {
        return $this->habilidades;
    }

    public function addHabilidad(Habilidad $habilidad): static
    {
        if (!$this->habilidades->contains($habilidad)) {
            $this->habilidades->add($habilidad);
            $habilidad->setUsuario($this);
        }

        return $this;
    }

    public function removeHabilidad(Habilidad $habilidad): static
    {
        if ($this->habilidades->removeElement($habilidad)) {
            // set the owning side to null (unless already changed)
            if ($habilidad->getUsuario() === $this) {
                $habilidad->setUsuario(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Idioma>
     */
    public function getIdiomas(): Collection
    {
        return $this->idiomas;
    }

    public function addIdioma(Idioma $idioma): static
    {
        if (!$this->idiomas->contains($idioma)) {
            $this->idiomas->add($idioma);
            $idioma->setUsuario($this);
        }
        return $this;
    }

    public function removeIdioma(Idioma $idioma): static
    {
        if ($this->idiomas->removeElement($idioma)) {
            // set the owning side to null (unless already changed)
            if ($idioma->getUsuario() === $this) {
                $idioma->setUsuario(null);
            }
        }
        return $this;
    }

    /**
     * @return Collection<int, Conocimiento>
     */
    public function getConocimientos(): Collection
    {
        return $this->conocimientos;
    }

    public function addConocimiento(Conocimiento $conocimiento): static
    {
        if (!$this->conocimientos->contains($conocimiento)) {
            $this->conocimientos->add($conocimiento);
            $conocimiento->setUsuario($this);
        }

        return $this;
    }

    public function removeConocimiento(Conocimiento $conocimiento): static
    {
        if ($this->conocimientos->removeElement($conocimiento)) {
            // set the owning side to null (unless already changed)
            if ($conocimiento->getUsuario() === $this) {
                $conocimiento->setUsuario(null);
            }
        }

        return $this;
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
            $postulacion->setUsuario($this);
        }

        return $this;
    }

    public function removePostulacion(Postulacion $postulacion): static
    {
        if ($this->postulaciones->removeElement($postulacion)) {
            // set the owning side to null (unless already changed)
            if ($postulacion->getUsuario() === $this) {
                $postulacion->setUsuario(null);
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
            $recomendacion->setUsuario($this);
        }

        return $this;
    }

    public function removeRecomendacion(Recomendacion $recomendacion): static
    {
        if ($this->recomendaciones->removeElement($recomendacion)) {
            // set the owning side to null (unless already changed)
            if ($recomendacion->getUsuario() === $this) {
                $recomendacion->setUsuario(null);
            }
        }

        return $this;
    }

    public function getCurriculum(): ?Curriculum
    {
        return $this->curriculum;
    }

    public function setCurriculum(Curriculum $curriculum): static
    {
        // set the owning side of the relation if necessary
        if ($curriculum->getUsuario() !== $this) {
            $curriculum->setUsuario($this);
        }

        $this->curriculum = $curriculum;

        return $this;
    }

    public function getAccount(): Account
    {
        return $this->account;
    }

    public function setAccount(Account $account): static
    {
        $this->account = $account;

        return $this;
    }
}
