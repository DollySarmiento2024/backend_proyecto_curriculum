<?php

namespace App\Controller;
use App\Entity\Idioma;
use App\Repository\IdiomaRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/idioma')]
final class IdiomaController extends AbstractController
{
    private IdiomaRepository $idiomaRepository;

        public function __construct(IdiomaRepository $idiomaRep)
        {
            $this->idiomaRepository = $idiomaRep;
        }

        //listar idiomas de un usuario
        #[Route('/usuario/{id}', name: 'api_idioma_usuario', methods: ['GET'])]
        public function indexByUser(int $id, IdiomaRepository $idiomaRep): JsonResponse
        {
            $idiomas = $idiomaRep->findBy(['usuario' => $id]);
            $data = [];
            foreach($idiomas as $idioma){
                $data[] = [
                    'id' => $idioma->getId(),
                    'idioma' => $idioma->getIdioma(),
                    'nivel' =>$idioma->getNivel(),
                    'usuario' =>$idioma->getUsuario()->getId(),
                ];
            }
            return  new JsonResponse(['idiomas' => $data], Response::HTTP_OK);
        }

    //crear nuevo idioma
    #[Route('/new', name: 'api_idioma_new', methods: ['POST'])]
    public function add(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        //si datos vacios o no están los obligatorios, devolver mensaje de error
        if(!$data || !isset($data->idioma)){
            return new JsonResponse(['error' => 'No se pudo guardar el registro'], Response::HTTP_BAD_REQUEST);
        }
        $this->idiomaRepository->new(
            idioma: $data->idioma,
            nivel: $data->nivel,
            usuario: $data->usuario_id);


    return new JsonResponse([
         'status'=> 'Idioma registrada correctamente',
         'idioma' => [
             'idioma'=>$data->idioma,
             'nivel'=>$data->nivel,
             'usuario'=>$data->usuario_id
            ]
        ], Response::HTTP_CREATED);
    }

    //Mostrar datos de un idioma
    #[Route('/{id}', name: 'api_idioma_show', methods: ['GET'])]
    public function show(Idioma $idioma): JsonResponse
    {
        $data = [
            'id' => $idioma->getId(),
            'idioma' => $idioma->getIdioma(),
            'nivel' => $idioma->getNivel(),
            'usuario' => $idioma->getUsuario()->getId(),
        ];
        return new JsonResponse($data, Response::HTTP_OK);

    }

    //Editar un idioma
    #[Route('/edit/{id}', name: 'api_idioma_edit', methods: ['PUT', 'PATCH'])]
    public function edit(int $id, Request $request): JsonResponse
    {
        $idioma = $this->idiomaRepository->find($id);
        $data = json_decode($request->getContent());
        if($_SERVER['REQUEST_METHOD'] == 'PUT')
        {
            $mensaje = 'Idioma actualizado correctamente';
        } else {
            $mensaje = 'Idioma actualizado parcialmente';
        }
        if (!empty($data->idioma)) {
            $idioma->setIdioma($data->idioma);
        }
        if(!empty($data->nivel)) {
            $idioma->setNivel($data->nivel);
        }
        if (!empty($data->usuario_id)) {
            $idioma->setUsuario($data->usuario);
        }
        $this->idiomaRepository->save($idioma, true);
        return new JsonResponse(['status' => $mensaje], Response::HTTP_CREATED);
    }

    #[Route('/delete/{id}', name: 'api_idioma_delete', methods: ['DELETE'])]
    public function remove(Idioma $idioma): JsonResponse
    {
        $idioma = $idioma->getIdioma();
        $this->idiomaRepository->remove($idioma, true);
        return new JsonResponse(['status' => 'idioma' . $idioma . 'borrada correctamente'], Response::HTTP_OK);
    }
}




