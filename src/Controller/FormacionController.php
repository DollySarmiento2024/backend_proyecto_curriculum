<?php

namespace App\Controller;
use App\Entity\Formacion;
use App\Repository\FormacionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/formacion')]
final class FormacionController extends AbstractController
{
   private FormacionRepository $formacionRepository;

   public function __construct(FormacionRepository $formacionRep)
   {
       $this->formacionRepository = $formacionRep;
   }

    //listar formaciones de un usuario
    #[Route('/usuario/{id}', name: 'api_usuario_usuario', methods: ['GET'])]
    public function indexByUser(int $id, FormacionRepository $formacionRep): JsonResponse
    {
       $formaciones = $formacionRep->findBy(['usuario' => $id]);
       $data = [];
       foreach ($formaciones as $formacion) {
           $data[] = [
               'id' => $formacion->getId(),
               'titulo' => $formacion->getTitulo(),
               'centro' =>$formacion->getCentro(),
               'fecha_inicio' =>$formacion->getFechaInicio(),
               'fecha_fin' =>$formacion->getFechaFin(),
               'descripcion' =>$formacion->getDescripcion(),
               'usuario' =>$formacion->getUsuario()->getId(),
           ];
       }
       return new JsonResponse(['formaciones' => $data], Response::HTTP_OK );
    }

   //crear nueva formacion
    #[Route('/new', name: 'api_formacion_new', methods: ['POST'])]
    public function add(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        //si datos o no están los obligatorios, devolver mensaje de error
        if(!$data || !isset($data->titulo, $data->centro)){
            return new JsonResponse(['error' => 'No se pudo crear la formación'], Response::HTTP_BAD_REQUEST);
        }
        $this->formacionRepository->new(
            titulo: $data->titulo,
            centro: $data->centro,
            fecha_inicio: $data->fecha_inicio,
            fecha_fin: $data->fecha_fin,
            descripcion: $data->descripcion,
            usuario: $data->usuario_id);

        return new JsonResponse([
            'status' => 'Formación creada correctamente',
            'formacion' => [
                'titulo' => $data->titulo,
                'centro' => $data->centro,
                'fecha_inicio' => $data->fecha_inicio,
                'fecha_fin' => $data->fecha_fin,
                'descripcion' => $data->descripcion,
                'usuario' => $data->usuario_id,
            ]
        ], Response::HTTP_CREATED);

    }

    //Mostrar datos de una formación
    #[Route('/{id}', name: 'api_formacion_show', methods: ['GET'])]
    public function show(Formacion $formacion): JsonResponse
    {
        $data = [
            'id' => $formacion->getId(),
            'titulo' => $formacion->getTitulo(),
            'centro' => $formacion->getCentro(),
            'fecha_inicio' => $formacion->getFechaInicio(),
            'fecha_fin' => $formacion->getFechaFin(),
            'descripcion' => $formacion->getDescripcion(),
            'usuario' => $formacion->getUsuario()->getId(),
        ];
        return new JsonResponse($data, Response::HTTP_OK);
    }

    //Editar una formacion
    #[Route('/edit/{id}', name: 'api_formacion_edit', methods: ['PUT', 'PATCH'])]
    public function edit(int $id, Request $request): JsonResponse
    {
        $formacion = $this->formacionRepository->find($id);
        $data = Json_decode($request->getContent());
        if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
            $mensaje = 'Formación actualizada satisfactoriamente';
        } else {
            $mensaje = 'Formación actualizada parcialmente';
        }
        if (!empty($data->titulo)) {
            $formacion->setTitulo($data->titulo);
        }
        if (!empty($data->centro)) {
            $formacion->setCentro($data->centro);
        }
        if (!empty($data->fecha_inicio)) {
            $formacion->setFechaInicio($data->fecha_inicio);
        }
        if (!empty($data->fecha_fin)) {
            $formacion->setFechaFin($data->fecha_fin);
        }
        if (!empty($data->descripcion)) {
            $formacion->setDescripcion($data->descripcion);
        }
        if (!empty($data->usuario_id)) {
            $formacion->setUsuario($data->usuario);
        }
        $this->formacionRepository->save($formacion, true);
        return new JsonResponse(['status' => $mensaje], Response::HTTP_CREATED);
    }

    #[Route('/delete/{id}', name: 'api_formacion_delete', methods: ['DELETE'])]
    public function remove(Formacion $formacion): JsonResponse
    {
        $titulo = $formacion->getTitulo();
        $this->formacionRepository->remove($formacion, true);
        return new JsonResponse(['status' => 'formacion' . $titulo . 'Formación eliminada satisfactoriamente'], Response::HTTP_OK);
    }
}

/*
  #[Route('/formacion', name: 'app_formacion')]
    public function index(): Response
    {
        return $this->render('formacion/index.html.twig', [
            'controller_name' => 'FormacionController',
        ]);
    }
 */