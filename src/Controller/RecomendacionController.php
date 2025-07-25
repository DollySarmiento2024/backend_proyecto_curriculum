<?php

namespace App\Controller;

use App\Entity\Recomendacion;
use App\Repository\RecomendacionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/recomendacion')]
final class RecomendacionController extends AbstractController
{
    private RecomendacionRepository $recomendacionRepository;

    public function __construct(RecomendacionRepository $recomendacionRepo)
    {
        $this->recomendacionRepository = $recomendacionRepo;
    }

    //listar las recomendaciones
    #[Route(name: 'app_recomendacion', methods: ['GET'])]
    public function index(RecomendacionRepository $recomendacionRepo): JsonResponse
    {
        $recomendaciones = $recomendacionRepo->findAll();
        $data = [];
        foreach ($recomendaciones as $recomendacion) {
            $data[] = [
                'id' => $recomendacion->getId(),
                'score' => $recomendacion->getScore(),
                'fecha' => $recomendacion->getFecha(),
                'usuario' => $recomendacion->getUsuario()->getNombre(),
                'oferta' => $recomendacion->getOferta()->getId(),
            ];
        }
        return new JsonResponse(['recomendaciones' => $data], Response::HTTP_OK);
    }

    //crear nueva recomendacion
    #[Route('new', name: 'app_recomencion_new', methods: ['POST'])]
    public function add(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent());

        //si datos vacios o no están los obligatorios, devolver mensaje de error
        if (!$data || !isset($data->score, $data->fecha)) {
            return new JsonResponse(['error' => 'No se pudo guardar el registro'], Response::HTTP_BAD_REQUEST);
        }

        $this->recomendacionRepository->new(
            score: $data->score,
            fecha: $data->fecha);

        return new JsonResponse([
            'status' => 'Usuario registrado correctamente',
            'curriculum' => [
                'score' => $data->score,
                'fecha' => $data->fecha,
            ]
        ], Response::HTTP_CREATED);
    }

    //mostrar datos de una recomendacion
    #[Route('/{id}', name: 'api_recomendacion_show', methods: ['GET'])]
    public function show(Recomendacion $recomendacion): JsonResponse
    {
        //usuario
        $data_usuarios = [];
        $usuarios = $recomendacion->getUsuario();
        foreach ($usuarios as $usuario) {
            $data_usuarios[] = [
                'id' => $usuario->getId(),
                'nombre' => $usuario->getNombre(),
                'apellidos' => $usuario->getCentro(),
                'email' => $usuario->getFechaInicio(),
                'telefono' => $usuario->getFechaFin(),
                'direccion' => $usuario->getDescripcion(),
                'redes_sociales' => $usuario->setRedesSociales(),
                'foto' => $usuario->getFoto(),
                'resumen_perfil' => $usuario->getResumenPerfil(),
            ];
        }

        //oferta empleo
        $data_oferta_empleo = [];
        $oferta_empleos = $recomendacion->getOferta();
        foreach ($oferta_empleos as $ofertaEmpleo) {
            $data_usuarios[] = [
                'id' => $ofertaEmpleo->getId(),
                'titulo' => $ofertaEmpleo->getTitulo(),
                'descripcion' => $ofertaEmpleo->getDescripcion(),
                'ubicacion' => $ofertaEmpleo->getUbicacion(),
                'tipo_contrato' => $ofertaEmpleo->getTipoContrato(),
                'salario' => $ofertaEmpleo->getSalario(),
                'fecha_publicacion' => $ofertaEmpleo->getFechaPublicacion(),
            ];
        }

        $data = [
            'id' => $recomendacion->getId(),
            'score' => $recomendacion->getScore(),
            'fecha' => $recomendacion->getFecha(),
            'usuario' => [$data_usuarios],
            'oferta' => [$data_oferta_empleo]
        ];
        return new JsonResponse ($data, Response::HTTP_OK);
    }


        //editar un usuario
    #[Route('/edit/{id}', name: 'api_recomendacion_edit', methods: ['PUT', 'PATCH'])]
    public  function edit(int $id, Request $request): JsonResponse
    {
        $recomendacion = $this->recomendacionRepository->find($id);
        $data = json_decode($request->getContent());

       //si datos vacios, devolver mensaje de error
        if (!$data) {
            return new JsonResponse(['error' => 'No se pudo editar el registro'], Response::HTTP_BAD_REQUEST);
        }

       if ($_SERVER['REQUEST_METHOD'] == 'PUT')
       {
           $mensaje = 'Recomendacion actualizada correctamente';
       } else {
           $mensaje = 'Recomendacion actualizada parcialmente';
       }
       if (!empty($data->score)){
           $recomendacion->setScore($data->score);
       }
       if (!empty($data->fecha)){
            $recomendacion->setFecha($data->fecha);
       }
       $this->recomendacionRepository->save($recomendacion, true);
       return new JsonResponse(['status' => $mensaje], Response::HTTP_CREATED);
    }

    #[Route('/delete/{id}', name: 'api_recomendacion_delete', methods: ['DELETE'])]
    public function remove(Recomendacion $recomendacion): JsonResponse
    {
        $score = $recomendacion->getScore();
        $this->recomendacionRepository->remove($recomendacion, true);
        return new JsonResponse(['status' => $recomendacion . '$score' . 'Usuario eliminado correctamente'], Response::HTTP_OK);
    }
}


//#[Route('/recomendacion', name: 'app_recomendacion')]
//    public function index(): Response
//{
//    return $this->render('recomendacion/index.html.twig', [
//        'controller_name' => 'RecomendacionController',
//    ]);
//}