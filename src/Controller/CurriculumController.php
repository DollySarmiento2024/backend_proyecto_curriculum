<?php

namespace App\Controller;

use App\Entity\Curriculum;
use App\Repository\CurriculumRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/curriculum')]
final class CurriculumController extends AbstractController
{
    private CurriculumRepository $curriculumRepository;

    public function __construct(CurriculumRepository $curriculumRep)
    {
        $this->curriculumRepository = $curriculumRep;
    }

    //Listar los curriculum
    #[Route(name:'api_curriculum', methods: ['GET'])]
    public function index(CurriculumRepository $curriculumRep): JsonResponse
    {
        $curriculums = $curriculumRep->findAll();
        $data = [];
        foreach ($curriculums as $curriculum) {
            $data[] = [
                'id' => $curriculum->getId(),
                'formacion' =>$curriculum->getFormacion(),
                'experiencia' =>$curriculum->getExperiencia(),
                'habilidad' =>$curriculum->getHabilidad(),
                'idioma' =>$curriculum->getIdioma(),
                'conocimiento' =>$curriculum->getConocimiento()
            ];
        }
        return new JsonResponse(['curriculums' => $data], Response::HTTP_OK);
    }

    //crear nuevo curriculum
    #[Route('/new', name: 'api_curriculum', methods: ['POST'])]
    public function add(Request $request): JsonResponse
    {
        $data = Json_decode($request->getContent());

        //si datos vacios o no están los obligatorios, devolver mensaje de error
        if (!$data || !isset($data->formacion, $data->experiencia, $data->habilidad, $data->idioma, $data->conocimiento)) {
            return new JsonResponse(['error' => 'No se pudo guardar el registro'], Response::HTTP_BAD_REQUEST);
        }

        $this->curriculumRepository->new(
            formacion: $data->formacion,
            experiencia: $data->experiencia,
            habilidad: $data->habilidad,
            idioma: $data->idioma,
            conocimiento: $data->conocimiento,
            usuario: $data->usuario_id);

        return new JsonResponse([
            'status' => 'Curriculum registrado correctamente',
            'curriculum' => [
                'formacion' => $data->formacion,
                'experiencia' => $data->experiencia,
                'habilidad' => $data->experiencia,
                'idioma' => $data->idioma,
                'conocimiento' => $data->conocimiento
            ]
        ], Response::HTTP_CREATED);
    }


    //Mostrar datos de un curriculum
    #[Route('/{id}', name: 'api_curriculum_show', methods: ['GET'])]
    public function show(Curriculum $curriculum): JsonResponse
    {

        //usuario
        $data_usuarios = [];
        $usuarios = $curriculum->getUsuarios();
        foreach ($usuarios as $usuario) {
            $data_usuarios[] = [
                'id' => $usuarios->getId(),
                'nombre' => $usuarios->getNombre(),
                'apellidos' => $usuarios->getApellidos(),
                'email' => $usuarios->getEmail(),
                'telefono' => $usuarios->getTelefono(),
                'direccion' => $usuario->getUsuario(),
                'redes_sociales' => $usuario->getRedesSociales(),
                'resumen_perfil' => $usuario->getResumenPerfil(),
                'foto' => $usuarios->getFoto(),
                'usuario' => $usuarios->getUsuario()->getId(),
            ];
        }
        return new JsonResponse($data_usuarios, Response::HTTP_OK);

        }

        //editar un curriculum
        #[Route('/edit/{id}', name: 'api_curriculum_edit', methods: ['PUT', 'PATCH'])]
        public function edit(int $id, Request $request): JsonResponse
        {
            $curriculum = $this->curriculumRepository->find($id);
            $data = json_decode($request->getContent());

           //si datos vacios, devolver mensaje de error
            if (!$data){
                return  new JsonResponse(['error' => 'No se pudo editar el curriculum'], Response::HTTP_BAD_REQUEST);
            }

            if ($_SERVER['REQUEST_METHOD'] === 'PUT')
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
            return new JsonResponse(['status' => $mensaje], Response::HTTP_CREATED);
        }

    #[Route('/delete/{id}', name: 'api_curriculum_delete', methods: ['DELETE'])]
    public function remove(Curriculum $curriculum): JsonResponse
    {
        $id = $curriculum->getId();
        $this->curriculumRepository->remove($curriculum, true);
        return new JsonResponse(['status' => 'curriculum' . $id . 'eliminado correctamente'], Response::HTTP_OK);

    }
}

//#[Route('/curriculum', name: 'app_curriculum')]
//    public function index(): Response
//    {
//        return $this->render('curriculum/index.html.twig', [
//            'controller_name' => 'CurriculumController',
//        ]);
//    }