<?php

namespace App\Controller;
use App\Entity\Habilidad;
use App\Repository\HabilidadRepository;
use App\Repository\UsuarioRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/habilidad')]
final class HabilidadController extends AbstractController
{
    private HabilidadRepository $habilidadRepository;
    private UsuarioRepository $usuarioRepository;

    public function __construct(HabilidadRepository $habilidadRep, UsuarioRepository $usuarioRep)
    {
        $this->habilidadRepository = $habilidadRep;
        $this->usuarioRepository = $usuarioRep;
    }

    //listar habilidades de un usuario
   #[Route('/usuario/{id}', name: 'api_habilidad_usuario', methods: ['GET'])]
    public function indexByUser(int $id): JsonResponse
    {
       $usuario = $this->usuarioRepository->find($id);
       $habilidades = $this->habilidadRepository->findBy(['usuario' => $usuario]);
       $data = [];
       foreach ($habilidades as $habilidad) {
           $data[] = [
               'id' => $habilidad->getId(),
               'nombre' => $habilidad->getNombre(),
               'nivel' => $habilidad->getNivel(),
               'descripcion' => $habilidad->getDescripcion(),
               'id_usuario' => $habilidad->getUsuario()->getId(),
           ];
      }
       return new JsonResponse(['habilidades' => $data], Response::HTTP_OK);
    }

    //crear nueva habilidad
    #[Route(name: 'api_habilidad_new', methods: ['POST'])]
    public function add(Request $request):JsonResponse
    {
        $data = json_decode($request->getContent());

        //si datos vacios o no están los obligatorios, devolver mensaje de error
        if (!$data || !isset($data->nombre)) {
            return new JsonResponse(['error' => 'No se pudo guardar el registro'], Response::HTTP_BAD_REQUEST);
        }

        //obtenemos el usuario al que hace referencia
        $usuario = $this->usuarioRepository->find($data->id_usuario);

        $new_id = $this->habilidadRepository->new(
            nombre: $data->nombre,
            nivel: $data->nivel,
            descripcion: $data->descripcion,
            usuario: $usuario);

        return new JsonResponse([
            'status' => 'Habilidad registrada correctamente',
            'habilidad' => [
                'nombre' => $data->nombre,
                'nivel' => $data->nivel,
                'descripcion' => $data->descripcion,
                'id_usuario' => $data->id_usuario,
            ]
        ], Response::HTTP_CREATED);

    }

    //Mostrar datos de una habilidad
    #[Route('/{id}', name: 'api_habilidad_show', methods: ['GET'])]
    public function show(Habilidad $habilidad): JsonResponse
    {
        $data = [
            'id' => $habilidad->getId(),
            'nombre' => $habilidad->getNombre(),
            'nivel' => $habilidad->getNivel(),
            'descripcion' => $habilidad->getDescripcion(),
            'id_usuario' => $habilidad->getUsuario()->getId(),
        ];
        return new JsonResponse($data, Response::HTTP_OK);
    }

    //Editar una habilidad
    #[Route('/{id}', name: 'api_habilidad_edit', methods: ['PUT', 'PATCH'])]
    public function edit(int $id, Request $request): JsonResponse
    {
        $habilidad = $this->habilidadRepository->find($id);
        $data = json_decode($request->getContent());
        if ($request->getMethod() == 'PUT')
        {
            $mensaje = 'Habilidad actualizada satisfactoriamente';
        } else {
            $mensaje = 'Habilidad actualizada parcialmente';
        }
        if(!empty($data->nombre)){
            $habilidad->setNombre($data->nombre);
        }
        if(!empty($data->nivel)){
            $habilidad->setNivel($data->nivel);
        }
        if(!empty($data->descripcion)){
            $habilidad->setDescripcion($data->descripcion);
        }
        if(!empty($data->usuario_id)){
            $habilidad->setUsuario($data->usuario);
        }
        $this->habilidadRepository->save($habilidad, true);
        return new JsonResponse(['status' => $mensaje], Response::HTTP_OK);
    }

    #[Route('/{id}', name: 'api_habilidad_delete', methods: ['DELETE'])]
    public function remove(Habilidad $habilidad): JsonResponse
    {
        $nombre = $habilidad->getNombre();
        $this->habilidadRepository->remove($habilidad, true);
        return new JsonResponse(['status' => 'habilidad ' . $nombre . ' eliminada correctamente'], Response::HTTP_OK);
    }
}

//    #[Route('/habilidad', name: 'app_habilidad')]
//    public function index(): Response
//    {
//        return $this->render('habilidad/index.html.twig', [
//            'controller_name' => 'HabilidadController',
//        ]);
//    }