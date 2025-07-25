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

#[Route('/conocimiento', name: 'api_conocimiento', methods: ['GET'])]
final class ConocimientoController extends AbstractController
{
   private ConocimientoRepository $conocimientoRepository;
   private UsuarioRepository $usuarioRepository;

   public function __construct(ConocimientoRepository $conocimientoRep)
   {
       $this->conocimientoRepository = $conocimientoRep;
   }

    //Listar conocimiento de un usuario
    #[Route('/usuario/{id}', name: 'api_usuario_usuario', methods: ['GET'])]
    public function indexByUser(int $id, ConocimientoRepository $conocimientoRep): JsonResponse
    {
        $conocimientos = $conocimientoRep->findBy(['usuario' => $id]);
        $data = [];
        foreach ($conocimientos as $conocimiento) {
            $data[] = [
                'id' => $conocimiento->getId(),
                'nombre' => $conocimiento->getNombre(),
                'nivel' =>$conocimiento->getNivel(),
                'descripcion' =>$conocimiento->getDescripcion(),
                'usuario' =>$conocimiento->getUsuario()->getId(),
            ];
        }
        return new JsonResponse(['conocimientos' => $data], Response::HTTP_OK);
    }

    //crear nuevo conocimiento
    #[Route('/new', name: 'api_conocimiento_new', methods: ['POST'])]
    public function add(Request $request): JsonResponse
    {
        $data = Json_decode($request->getContent(), true);

        //si datos vacios o no están los obligatorios, devolver mensaje de error
        if (!$data || !isset($data->nombre)) {
            return new JsonResponse(['error' => 'No se pudo guardar el registro'], Response::HTTP_BAD_REQUEST);
        }

        //obtenemos el usuario al que hace referencia
        $usuario =  $this->usuarioRepository->findOneBy(['id' => $data->id_usuario]);

        $this->conocimientoRepository->new(
            nombre: $data->nombre,
            nivel: $data->nivel,
            descripcion: $data->descripcion,
            usuario: $usuario);

        return new JsonResponse([
            'status' => 'Conocimiento creado correctamente',
            'conocimiento' => [
                'nombre' => $data->nombre,
                'nivel' => $data->nivel,
                'descripcion' => $data->descripcion,
                'usuario' => $data->usuario_id
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
            'usuario' => $conocimiento->getUsuario()->getId(),
        ];
        return new JsonResponse($data, Response::HTTP_OK);
    }

    //Editar un conocimiento
    #[Route('/edit/{id}', name: 'api_conocimiento_edit', methods: ['PUT', 'PATCH'])]
    public function edit(int $id, Request $request): JsonResponse
    {
        $conocimiento = $this->conocimientoRepository->find($id);
        $data = Json_decode($request->getContent());
        if ($_SERVER['REQUEST_METHOD'] == 'PUT')
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
        if(!empty($data->usuario_id)) {
            $conocimiento->setUsuario($data->usuario);
        }
        $this->conocimientoRepository->save($conocimiento, true);
        return new JsonResponse(['status' => $mensaje], Response::HTTP_CREATED);
    }

    #[Route('/delete/{id}', name: 'api_conocimiento_delete', methods: ['DELETE'])]
    public function remove(conocimiento $conocimiento): JsonResponse
    {
        $nombre = $conocimiento->getNombre();
        $this->conocimientoRepository->remove($conocimiento, true);
        return new JsonResponse(['status' => 'conocimiento' . $nombre . 'Conocimiento eliminado satisfactoriamente'], Response::HTTP_OK);
    }
}

/* #[Route('/conocimiento', name: 'app_conocimiento')]
    public function index(): Response
    {
        return $this->render('conocimiento/index.html.twig', [
            'controller_name' => 'ConocimientoController',
        ]);
    }*/