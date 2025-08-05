<?php

namespace App\Controller;

use App\Entity\Curriculum;
use App\Repository\CurriculumRepository;
use App\Repository\UsuarioRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/curriculum')]
final class CurriculumController extends AbstractController
{
    private CurriculumRepository $curriculumRepository;
    private UsuarioRepository $usuarioRepository;

    public function __construct(CurriculumRepository $curriculumRep, UsuarioRepository $usuarioRep)
    {
        $this->curriculumRepository = $curriculumRep;
        $this->usuarioRepository = $usuarioRep;
    }

    //Listar el curriculum de un usuario
    #[Route('/usuario/{id}', name:'api_curriculum_usuario', methods: ['GET'])]
    public function index(int $id): JsonResponse
    {
        $usuario = $this->usuarioRepository->find($id);
        $curriculum = $this->curriculumRepository->findOneBy(['usuario' => $usuario]);

        $data =[
                'id' => $curriculum->getId(),
                'formacion' =>$curriculum->getFormacion(),
                'experiencia' =>$curriculum->getExperiencia(),
                'habilidad' =>$curriculum->getHabilidad(),
                'idioma' =>$curriculum->getIdioma(),
                'conocimiento' =>$curriculum->getConocimiento(),
                'id_usuario' =>$curriculum->getUsuario()->getId(),
        ];

        return new JsonResponse(['curriculums' => $data], Response::HTTP_OK);
    }

    //crear nuevo curriculum
    #[Route(name: 'api_curriculum', methods: ['POST'])]
    public function add(Request $request): JsonResponse
    {
        $data = Json_decode($request->getContent());

        //si datos vacios o no están los obligatorios, devolver mensaje de error
        if (!$data || !isset($data->formacion, $data->experiencia, $data->habilidad, $data->idioma, $data->conocimiento)) {
            return new JsonResponse(['error' => 'No se pudo guardar el registro'], Response::HTTP_BAD_REQUEST);
        }

        //Obtenemos el curriculum al que hace referencia
        $usuario = $this->usuarioRepository->find($data->id_usuario);

        $new_id = $this->curriculumRepository->new(
            formacion: $data->formacion,
            experiencia: $data->experiencia,
            habilidad: $data->habilidad,
            idioma: $data->idioma,
            conocimiento: $data->conocimiento,
            usuario: $usuario);

        return new JsonResponse([
            'status' => 'Curriculum registrado correctamente',
            'curriculum' => [
                'id' => $new_id,
                'formacion' => $data->formacion,
                'experiencia' => $data->experiencia,
                'habilidad' => $data->experiencia,
                'idioma' => $data->idioma,
                'conocimiento' => $data->conocimiento,
                'id_usuario' => $data->id_usuario
            ]
        ], Response::HTTP_CREATED);
    }

    //Mostrar datos de un curriculum
    #[Route('/{id}', name: 'api_curriculum_show', methods: ['GET'])]
    public function show(Curriculum $curriculum): JsonResponse
    {
        $data =[
            'id' => $curriculum->getId(),
            'formacion' =>$curriculum->getFormacion(),
            'experiencia' =>$curriculum->getExperiencia(),
            'habilidad' =>$curriculum->getHabilidad(),
            'idioma' =>$curriculum->getIdioma(),
            'conocimiento' =>$curriculum->getConocimiento(),
            'id_usuario' =>$curriculum->getUsuario()->getId(),
        ];
        return new JsonResponse($data, Response::HTTP_OK);
        }

    //editar un curriculum
    #[Route('/{id}', name: 'api_curriculum_edit', methods: ['PUT', 'PATCH'])]
    public function edit(int $id, Request $request): JsonResponse
        {
            $curriculum = $this->curriculumRepository->find($id);
            $data = json_decode($request->getContent());

            if ($request->getMethod() == 'PUT')
            {
                $mensaje = 'Curriculum actualizado correctamente';
            } else {
                $mensaje = 'Curriculum actualizado parcialmente';
            }

            if (!empty($data->formacion)) {
                $curriculum->setFormacion($data->formacion);
            }
            if (!empty($data->experiencia)) {
                $curriculum->setExperiencia($data->experiencia);
            }
            if (!empty($data->habilidad)) {
                $curriculum->setHabilidad($data->habilidad);
            }
            if (!empty($data->idioma)) {
                $curriculum->setIdioma($data->idioma);
            }
            if (!empty($data->conocimiento)) {
                $curriculum->setConocimiento($data->conocimiento);
            }
            if (!empty($data->usuario_id)) {
                $curriculum->setUsuario($data->usuario_id);
            }
            $this->curriculumRepository->save($curriculum, true);
            return new JsonResponse(['status' => $mensaje], Response::HTTP_OK);
        }

    #[Route('/{id}', name: 'api_curriculum_delete', methods: ['DELETE'])]
    public function remove(Curriculum $curriculum): JsonResponse
    {
        $id = $curriculum->getId();
        $this->curriculumRepository->remove($curriculum, true);
        return new JsonResponse(['status' => 'curriculum con id ' . $id . ' eliminada correctamente'], Response::HTTP_OK);
    }
}

//#[Route('/curriculum', name: 'app_curriculum')]
//    public function index(): Response
//    {
//        return $this->render('curriculum/index.html.twig', [
//            'controller_name' => 'CurriculumController',
//        ]);
//    }