<?php

namespace App\Controller;
use App\Entity\Formacion;
use App\Repository\FormacionRepository;
use App\Repository\UsuarioRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/formacion')]
final class FormacionController extends AbstractController
{
   private FormacionRepository $formacionRepository;
   private UsuarioRepository $usuarioRepository;

   public function __construct(FormacionRepository $formacionRep, UsuarioRepository $usuarioRep)
   {
       $this->formacionRepository = $formacionRep;
       $this->usuarioRepository = $usuarioRep;
   }

    //listar formaciones de un usuario
    #[Route('/usuario/{id}', name: 'api_formacion_usuario', methods: ['GET'])]
    public function indexByUser(int $id): JsonResponse
    {
        $usuario = $this->usuarioRepository->find($id);
        $formaciones = $this->formacionRepository->findBy(['usuario' => $usuario]);
        $data = [];
        foreach ($formaciones as $formacion) {
            $data[] = [
                'id' => $formacion->getId(),
                'titulo' => $formacion->getTitulo(),
                'centro' =>$formacion->getCentro(),
                'fecha_inicio' =>$formacion->getFechaInicio(),
                'fecha_fin' =>$formacion->getFechaFin(),
                'descripcion' =>$formacion->getDescripcion(),
                'id_usuario' =>$formacion->getUsuario()->getId(),
            ];
        }
        return new JsonResponse(['formaciones' => $data], Response::HTTP_OK );
    }

    //crear nueva formacion
    #[Route(name: 'api_formacion_new', methods: ['POST'])]
    public function add(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent());

        //si datos o no están los obligatorios, devolver mensaje de error
        if(!$data || !isset($data->titulo, $data->centro)){
            return new JsonResponse(['error' => 'No se pudo crear la formación'], Response::HTTP_BAD_REQUEST);
        }

        //Obtenemos el usuario al que hace referencia
        $usuario = $this->usuarioRepository->find($data->id_usuario);

        $new_id = $this->formacionRepository->new(
            titulo: $data->titulo,
            centro: $data->centro,
            fecha_inicio: new DateTime($data->fecha_inicio),
            fecha_fin: new DateTime($data->fecha_fin),
            descripcion: $data->descripcion,
            usuario: $usuario);

        return new JsonResponse([
            'status' => 'Formación creada correctamente',
            'formacion' => [
                'id' => $new_id,
                'titulo' => $data->titulo,
                'centro' => $data->centro,
                'fecha_inicio' => $data->fecha_inicio,
                'fecha_fin' => $data->fecha_fin,
                'descripcion' => $data->descripcion,
                'id_usuario' => $data->id_usuario,
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
            'id_usuario' => $formacion->getUsuario()->getId(),
        ];
        return new JsonResponse($data, Response::HTTP_OK);
    }

    //Editar una formacion
    #[Route('/{id}', name: 'api_formacion_edit', methods: ['PUT', 'PATCH'])]
    public function edit(int $id, Request $request): JsonResponse
    {
        $formacion = $this->formacionRepository->find($id);
        $data = json_decode($request->getContent());
        //si datos vacios, devolver mensaje de error
        if (!$data){
            return  new JsonResponse(['error' => 'No se pudo editar el registro'], Response::HTTP_BAD_REQUEST);
        }
        if ($request->getMethod() == 'PUT')
        {
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
            $formacion->setFechaInicio(new DateTime($data->fecha_inicio));
        }
        if (!empty($data->fecha_fin)) {
            $formacion->setFechaFin(new DateTime($data->fecha_fin));
        }
        if (!empty($data->descripcion)) {
            $formacion->setDescripcion($data->descripcion);
        }
        $this->formacionRepository->save($formacion, true);
        return new JsonResponse(['status' => $mensaje], Response::HTTP_OK);
    }

    #[Route('/{id}', name: 'api_formacion_delete', methods: ['DELETE'])]
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