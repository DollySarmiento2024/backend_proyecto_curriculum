<?php

namespace App\Controller;
use App\Entity\Experiencia;
use App\Repository\ExperienciaRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/experiencia')]
final class ExperienciaController extends AbstractController
{
    private ExperienciaRepository $experienciaRepository;

    public function __construct(ExperienciaRepository $experienciaRep)
    {
        $this->experienciaRepository = $experienciaRep;
    }

    //Listar experiencias de un usuario
    #[Route('/usuario/{id}', name: 'api_experiencia_usuario', methods: ['GET'])]
    public function indexByUser(int $id, ExperienciaRepository $experienciaRep): JsonResponse
    {
        $experiencias = $experienciaRep->findBy(['usuario' => $id]);
        $data = [];
        foreach ($experiencias as $experiencia) {
            $data[] = [
                'id' => $experiencia->getId(),
                'puesto' => $experiencia->getPuesto(),
                'empresa' => $experiencia->getEmpresa(),
                'fecha_inicio' => $experiencia->getFechaInicio(),
                'fecha_fin' => $experiencia->getFechaFin(),
                'descripcion' => $experiencia->getDescripcion(),
                'usuario' => $experiencia->getUsuario()->getId(),
            ];
        }
        return new JsonResponse(['experiencias' => $data], Response::HTTP_OK);
    }

    //crear nueva experiencia
    #[Route('/new', name: 'api_experiencia_new', methods: ['POST'])]
    public function add(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        //si datos vacios o no están los obligatorios, devolver mensaje de error
        if (!$data || !isset($data->puesto, $data->empresa)) {
            return new JsonResponse(['error' => 'No se pudo guardar el registro'], Response::HTTP_BAD_REQUEST);
        }
        $this->experienciaRepository->new(
            puesto: $data->puesto,
            empresa: $data->empresa,
            fecha_inicio: $data->fechaInicio,
            fecha_fin: $data->fechaFin,
            descripcion: $data->descripcion,
            usuario: $data->usuario_id);

        return new JsonResponse([
            'status' => 'Experiencia registrada correctamente',
            'experiencia' => [
                'puesto' => $data->puesto,
                'empresa' => $data->empresa,
                'fechaInicio' => $data->fechaInicio,
                'fechaFin' => $data->fechaFin,
                'descripcion' => $data->descripcion,
                'usuario' => $data->usuario_id,
            ]
        ], Response::HTTP_CREATED);
    }

    //Mostrar datos de una experiencia
    #[Route('/{id}', name: 'api_experiencia_show', methods: ['GET'])]
    public function show(Experiencia $experiencia): JsonResponse
    {
        $data = [
            'id' => $experiencia->getId(),
            'puesto' => $experiencia->getPuesto(),
            'empresa' => $experiencia->getEmpresa(),
            'fecha_inicio' => $experiencia->getFechaInicio(),
            'fecha_fin' => $experiencia->getFechaFin(),
            'descripcion' => $experiencia->getDescripcion(),
            'usuario' => $experiencia->getUsuario()->getId(),
        ];
        return new JsonResponse($data, Response::HTTP_OK);
    }

    //Editar una experiencia
    #[Route('/edit/{id}', name: 'api_experiencia_edit', methods: ['PUT', 'PATCH'])]
    public function edit(int $id, Request $request): JsonResponse
    {
        $experiencia = $this->experienciaRepository->find($id);
        $data = Json_decode($request->getContent());
        if ($_SERVER['REQUEST_METHOD'] == 'PUT')
        {
            $mensaje = 'Experiencia actualizada correctamente';
        } else {
            $mensaje = 'Experiencia actualizada parcialmente';
        }
        if(!empty($data->puesto)) {
            $experiencia->setPuesto($data->puesto);
        }
        if(!empty($data->empresa)) {
            $experiencia->setEmpresa($data->empresa);
        }
        if(!empty($data->fechaInicio)) {
            $experiencia->setFechaInicio($data->fechaInicio);
        }
        if(!empty($data->fechaFin)) {
            $experiencia->setFechaFin($data->fechaFin);
        }
        if(!empty($data->descripcion)) {
            $experiencia->setDescripcion($data->descripcion);
        }
        if(!empty($data->usuario_id)) {
            $experiencia->setUsuario($data->usuario);
        }
        $this->experienciaRepository->save($experiencia, true);
        return new JsonResponse(['status' => $mensaje], Response::HTTP_CREATED);
    }

    #[Route('/delete/{id}', name: 'api_experiencia_delete', methods: ['DELETE'])]
    public function remove(experiencia $experiencia): JsonResponse
    {
       $puesto = $experiencia->getPuesto();
       $this->experienciaRepository->remove($experiencia, true);
       return new JsonResponse(['status' => 'experiencia' . $puesto . 'Experiencia eliminada satisfactoriamente'], Response::HTTP_OK);
    }
}

/*  #[Route('/experiencia', name: 'app_experiencia')]
    public function index(): Response
    {
        return $this->render('experiencia/index.html.twig', [
            'controller_name' => 'ExperienciaController',
        ]);
    }
 */