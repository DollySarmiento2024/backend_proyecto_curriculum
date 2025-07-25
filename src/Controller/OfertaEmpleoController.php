<?php

namespace App\Controller;
use App\Entity\OfertaEmpleo;
use App\Repository\OfertaEmpleoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/oferta_empleo')]
final class OfertaEmpleoController extends AbstractController
{
    private OfertaEmpleoRepository $ofertaEmpleoRepository;

    public function __construct(OfertaEmpleoRepository $ofertaEmpleoRep)
    {
        $this->ofertaEmpleoRepository = $ofertaEmpleoRep;
    }

    //listar todas las ofertas de empleo
    #[Route(name: 'api_oferta_empleo', methods: ['GET'])]
    public function index(OfertaEmpleoRepository $ofertaEmpleoRep): JsonResponse
    {
        $ofertaEmpleos = $ofertaEmpleoRep->findAll();
        $data = [];
        foreach ($ofertaEmpleos as $ofertaEmpleo) {
            $data[] = [
                'id' => $ofertaEmpleo->getId(),
                'titulo' => $ofertaEmpleo->getTitulo(),
                'descripcion' => $ofertaEmpleo->getDescripcion(),
                'ubicacion' => $ofertaEmpleo->getUbicacion(),
                'tipo_contrato' => $ofertaEmpleo->getTipoContrato(),
                'salario' => $ofertaEmpleo->getSalario(),
                'fecha_publicacion' => $ofertaEmpleo->getFechaPublicacion(),
                'empresa' => $ofertaEmpleo->getEmpresa()->getId(), //ToDo traer datos empresa
            ];
        }
        return new JsonResponse(['ofertaEmpleos' => $data], Response::HTTP_OK);
    }

    //listar ofertas de empleo de una empresa
    #[Route('/oferta_empleo/{id}', name: 'api_oferta_empleo', methods: ['GET'])]
    public function indexByEmpresa(int $id, OfertaEmpleoRepository $ofertaEmpleoRep): JsonResponse
    {
        $ofertas = $ofertaEmpleoRep->findBy(['empresa' => $id]);
        $data = [];
        foreach ($ofertas as $oferta) {
            $data[] = [
                'id' => $oferta->getId(),
                'nombre' => $oferta->getNombre(),
                'email' => $oferta->getEmail(),
                'telefono' => $oferta->getTelefono(),
                'direccion' => $oferta->getDireccion(),
                'ciudad' => $oferta->getCiudad(),
                'pais' => $oferta->getPais(),
                'sector' => $oferta->getSector(),
                'descripcion' => $oferta->getDescripcion(),
                'logo' => $oferta->getLogo(),
                'sitio_web' => $oferta->getSitioWeb(),
                'empresa' => $oferta->getEmpresa()->getId()
            ];
        }
        return new JsonResponse(['ofertas' => $data], Response::HTTP_OK );
    }

    //crear nuevo oferta de empleo
    #[Route('/new', name: 'api_oferta_empleo_new', methods: ['POST'])]
    public function add(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent());

        //si datos vacios o no están los obligatorios, devolver mensaje de error
        if (!$data || !isset($data->titulo, $data->descripcion, $data->tipo_contrato, $data->fecha_publicacion)) {
            return new JsonResponse(['error' => 'No se pudo guardar el registro'], Response::HTTP_BAD_REQUEST);
        }
        $this->ofertaEmpleoRepository->new(
            titulo: $data->titulo,
            descripcion: $data->descripcion,
            ubicacion: $data->ubicacion,
            tipo_contrato: $data->tipo_contrato,
            salario: $data->salario,
            fecha_publicacion: $data->fecha_publicacion,
            empresa: $data->empresa_id);

        return new JsonResponse([
            'status' => 'Oferta de empleo creado satisfactoriamente',
            'ofertaEmpleo' => [
                'titulo' => $data->titulo,
                'descripcion' => $data->descripcion,
                'ubicacion' => $data->ubicacion,
                'tipo_contrato' => $data->tipo_contrato,
                'salario' => $data->salario,
                'fecha_publicacion' => $data->fecha_publicacion,
                'empresa' => $data->empresa_id,
            ]
        ], Response::HTTP_CREATED);
    }

    //Mostrar datos de una oferta de empleo
    #[Route('/{id}', name: 'api_oferta_empleo_show', methods: ['GET'])]
    public function show(OfertaEmpleo $ofertaEmpleo): JsonResponse
    {

        //Empresa
        $data_empresa = [];
        $empresa = $ofertaEmpleo->getEmpresa();

            $data_empresa[] = [
                'id' => $empresa->getId(),
                'nombre' => $empresa->getNombre(),
                'email' => $empresa->getEmail(),
                'telefono' => $empresa->getTelefono(),
                'direccion' => $empresa->getDireccion(),
                'ciudad' => $empresa->getCiudad(),
                'pais' => $empresa->getPais(),
                'sector' => $empresa->getSector(),
                'descripcion' => $empresa->getDescripcion(),
                'logo' => $empresa->getLogo(),
                'sitio_web' => $empresa->getSitioWeb(),
            ];


        $data = [
            'id' => $ofertaEmpleo->getId(),
            'titulo' => $ofertaEmpleo->getTitulo(),
            'descripcion' => $ofertaEmpleo->getDescripcion(),
            'ubicacion' => $ofertaEmpleo->getUbicacion(),
            'tipo_contrato' => $ofertaEmpleo->getTipoContrato(),
            'salario' => $ofertaEmpleo->getSalario(),
            'fecha_publicacion' => $ofertaEmpleo->getFechaPublicacion(),
            'empresa' => [$data_empresa]
        ];
        return new JsonResponse( $data, Response::HTTP_OK);
    }

    //editar una oferta
    #[Route('/edit/{id}', name: 'api_oferta_empleo_edit', methods: ['PUT', 'PATCH'])]
    public function edit($id, Request $request): JsonResponse
    {
        $ofertaEmpleo = $this->ofertaEmpleoRepository->find($id);
        $data = json_decode($request->getContent());
        if ($_SERVER['REQUEST_METHOD'] === 'PUT')
        {
            $mensaje = 'Oferta empleo actualizado satisfactoriamente';
        } else {
            $mensaje = 'Oferta empleo actualizado parcialmente';
        }
        if (!empty($data->titulo)) {
            $ofertaEmpleo->setTitulo($data->titulo);
        }
        if (!empty($data->descripcion)) {
            $ofertaEmpleo->setDescripcion($data->descripcion);
        }
        if (!empty($data->ubicacion)) {
            $ofertaEmpleo->setUbicacion($data->ubicacion);
        }
        if (!empty($data->tipo_contrato)) {
            $ofertaEmpleo->setTipoContrato($data->tipo_contrato);
        }
        if (!empty($data->salario)) {
            $ofertaEmpleo->setSalario($data->salario);
        }
        if (!empty($data->fecha_publicacion)) {
            $ofertaEmpleo->setFechaPublicacion($data->fecha_publicacion);
        }
        if (!empty($data->empresa_id)) {
            $ofertaEmpleo->setEmpresa($data->empresa);
        }
        $this->ofertaEmpleoRepository->save($ofertaEmpleo, true);
        return new JsonResponse(['status' => $mensaje], Response::HTTP_CREATED);
    }

    #[Route('/delete/{id}', name: 'api_oferta_empleo_delete', methods: ['DELETE'])]
    public function remove(OfertaEmpleo $ofertaEmpleo): JsonResponse
    {
        $titulo = $ofertaEmpleo->getTitulo();
        $this->ofertaEmpleoRepository->remove($ofertaEmpleo, true);
        return new JsonResponse(['status' => 'ofertaEmpleo' . $titulo. 'Oferta empleo eliminado satisfactoriamente'], Response::HTTP_OK);
    }
}