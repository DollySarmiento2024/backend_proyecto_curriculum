<?php

namespace App\Controller;
use App\Entity\Idioma;
use App\Repository\IdiomaRepository;
use App\Repository\UsuarioRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/api/idioma')]
#[IsGranted('ROLE_USER')]
final class IdiomaController extends AbstractController
{
    private IdiomaRepository $idiomaRepository;
    private UsuarioRepository $usuarioRepository;

    public function __construct(IdiomaRepository $idiomaRep, UsuarioRepository $usuarioRep)
        {
            $this->idiomaRepository = $idiomaRep;
            $this->usuarioRepository = $usuarioRep;
        }

    //listar idiomas de un usuario
    #[Route('/usuario/{id}', name: 'api_idioma_usuario', methods: ['GET'])]
    public function indexByUser(int $id): JsonResponse
        {
            $usuario = $this->usuarioRepository->find($id);
            $idiomas = $this->idiomaRepository->findBy(['usuario' => $usuario]);
            $data = [];
            foreach($idiomas as $idioma){
                $data[] = [
                    'id' => $idioma->getId(),
                    'nombre' => $idioma->getNombre(),
                    'nivel' =>$idioma->getNivel(),
                    'id_usuario' =>$idioma->getUsuario()->getId(),
                ];
            }
            return  new JsonResponse(['idiomas' => $data], Response::HTTP_OK);
        }

    //crear nuevo idioma
    #[Route(name: 'api_idioma_new', methods: ['POST'])]
    public function add(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent());

        //si datos vacios o no están los obligatorios, devolver mensaje de error
        if(!$data || !isset($data->nombre)){
            return new JsonResponse(['error' => 'No se pudo guardar el registro'], Response::HTTP_BAD_REQUEST);
        }

        //obtenemos el usuario al que hace referencia
        $usuario =  $this->usuarioRepository->find($data->id_usuario);

        $new_id = $this->idiomaRepository->new(
            nombre: $data->nombre,
            nivel: $data->nivel,
            usuario: $usuario);


    return new JsonResponse([
         'status'=> 'Idioma registrado correctamente',
         'idioma' => [
             'nombre'=>$data->nombre,
             'nivel'=>$data->nivel,
             'id_usuario'=>$data->id_usuario
            ]
        ], Response::HTTP_CREATED);
    }

    //Mostrar datos de un idioma
    #[Route('/{id}', name: 'api_idioma_show', methods: ['GET'])]
    public function show(Idioma $idioma): JsonResponse
    {
        $data = [
            'id' => $idioma->getId(),
            'nombre' => $idioma->getNombre(),
            'nivel' => $idioma->getNivel(),
            'id_usuario' => $idioma->getUsuario()->getId(),
        ];
        return new JsonResponse($data, Response::HTTP_OK);

    }

    //Editar un idioma
    #[Route('/{id}', name: 'api_idioma_edit', methods: ['PUT', 'PATCH'])]
    public function edit(int $id, Request $request): JsonResponse
    {
        $idioma = $this->idiomaRepository->find($id);
        $data = json_decode($request->getContent());
        if($request->getMethod() == 'PUT')
        {
            $mensaje = 'Idioma actualizado correctamente';
        } else {
            $mensaje = 'Idioma actualizado parcialmente';
        }
        if (!empty($data->nombre)) {
            $idioma->setNombre($data->nombre);
        }
        if(!empty($data->nivel)) {
            $idioma->setNivel($data->nivel);
        }
        $this->idiomaRepository->save($idioma, true);
        return new JsonResponse(['status' => $mensaje], Response::HTTP_OK);
    }

    #[Route('/{id}', name: 'api_idioma_delete', methods: ['DELETE'])]
    public function remove(Idioma $idioma): JsonResponse
    {
        $nombre = $idioma->getNombre();
        $this->idiomaRepository->remove($idioma, true);
        return new JsonResponse(['status' => 'idioma ' . $nombre . ' eliminada correctamente'], Response::HTTP_OK);
    }
}




