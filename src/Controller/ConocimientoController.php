<?php

namespace App\Controller;
use App\Entity\Conocimiento;
use App\Repository\ConocimientoRepository;
use App\Repository\UsuarioRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/conocimiento')]
final class ConocimientoController extends AbstractController
{
   private ConocimientoRepository $conocimientoRepository;
   private UsuarioRepository $usuarioRepository;

   public function __construct(ConocimientoRepository $conocimientoRep, UsuarioRepository $usuarioRep)
   {
       $this->conocimientoRepository = $conocimientoRep;
       $this->usuarioRepository = $usuarioRep;
   }

    //Listar conocimiento de un usuario
    #[Route('/usuario/{id}', name: 'api_conocimiento_usuario', methods: ['GET'])]
    public function indexByUser(int $id): JsonResponse
    {
        $usuario = $this->usuarioRepository->find($id);
        $conocimientos = $this->conocimientoRepository->findBy(['usuario' => $usuario]);
        $data = [];
        foreach ($conocimientos as $conocimiento) {
            $data[] = [
                'id' => $conocimiento->getId(),
                'nombre' => $conocimiento->getNombre(),
                'nivel' =>$conocimiento->getNivel(),
                'descripcion' =>$conocimiento->getDescripcion(),
                'id_usuario' =>$conocimiento->getUsuario()->getId(),
            ];
        }
        return new JsonResponse(['conocimientos' => $data], Response::HTTP_OK);
    }

    //crear nuevo conocimiento
    #[Route(name: 'api_conocimiento_new', methods: ['POST'])]
    public function add(Request $request): JsonResponse
    {
        $data = Json_decode($request->getContent(), true);

        //si datos vacios o no están los obligatorios, devolver mensaje de error
        if (!$data || !isset($data->nombre)) {
            return new JsonResponse(['error' => 'No se pudo guardar el registro'], Response::HTTP_BAD_REQUEST);
        }

        //obtenemos el usuario al que hace referencia
        $usuario =  $this->usuarioRepository->find($data->id_usuario);

        $new_id = $this->conocimientoRepository->new(
            nombre: $data->nombre,
            nivel: $data->nivel,
            descripcion: $data->descripcion,
            usuario: $usuario);

        return new JsonResponse([
            'status' => 'Conocimiento creado correctamente',
            'conocimiento' => [
                'id' => $new_id,
                'nombre' => $data->nombre,
                'nivel' => $data->nivel,
                'descripcion' => $data->descripcion,
                'id_usuario' => $data->id_usuario
            ]
        ], Response::HTTP_CREATED);

    }

    //Mostrar datos de un conocimiento
    #[Route('/{id}', name: 'api_conocimiento_show', methods: ['GET'])]
    public function show(Conocimiento $conocimiento): JsonResponse
    {
        $data = [
            'id' => $conocimiento->getId(),
            'nombre' => $conocimiento->getNombre(),
            'nivel' => $conocimiento->getNivel(),
            'descripcion' => $conocimiento->getDescripcion(),
            'id_usuario' => $conocimiento->getUsuario()->getId(),
        ];
        return new JsonResponse($data, Response::HTTP_OK);
    }

    //Editar un conocimiento
    #[Route('/{id}', name: 'api_conocimiento_edit', methods: ['PUT', 'PATCH'])]
    public function edit(int $id, Request $request): JsonResponse
    {
        $conocimiento = $this->conocimientoRepository->find($id);
        $data = Json_decode($request->getContent());

        //si datos vacios, devolver mensaje de error
        if(!$data){
            return new JsonResponse(['error' => 'No se pudo editar el registro'], Response::HTTP_BAD_REQUEST);
        }

        if ($request->getMethod() == 'PUT')
        {
            $mensaje = 'Conocimiento actualizado satisfactoriamente';
        } else {
            $mensaje = 'Conocimiento actualizado parcialmente';
        }
        if(!empty($data->nombre)) {
            $conocimiento->setNombre($data->nombre);
        }
        if(!empty($data->nivel)) {
            $conocimiento->setNivel($data->nivel);
        }
        if(!empty($data->descripcion)) {
            $conocimiento->setDescripcion($data->descripcion);
        }
        $this->conocimientoRepository->save($conocimiento, true);
        return new JsonResponse(['status' => $mensaje], Response::HTTP_OK);
    }

    #[Route('/{id}', name: 'api_conocimiento_delete', methods: ['DELETE'])]
    public function remove(Conocimiento $conocimiento): JsonResponse
    {
        $nombre = $conocimiento->getNombre();
        $this->conocimientoRepository->remove($conocimiento, true);
        return new JsonResponse(['status' => 'conocimiento ' . $nombre . ' eliminado satisfactoriamente'], Response::HTTP_OK);
    }
}

/* #[Route('/conocimiento', name: 'app_conocimiento')]
    public function index(): Response
    {
        return $this->render('conocimiento/index.html.twig', [
            'controller_name' => 'ConocimientoController',
        ]);
    }*/