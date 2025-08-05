<?php

namespace App\Controller;

use App\Entity\Recomendacion;
use App\Repository\OfertaEmpleoRepository;
use App\Repository\RecomendacionRepository;
use App\Repository\UsuarioRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/recomendacion')]
final class RecomendacionController extends AbstractController
{
    private RecomendacionRepository $recomendacionRepository;
    private UsuarioRepository $usuarioRepository;
    private OfertaEmpleoRepository $ofertaEmpleoRepository;

    public function __construct(RecomendacionRepository $recomendacionRep, UsuarioRepository $usuarioRep, OfertaEmpleoRepository $ofertaEmpleoRep)
    {
        $this->recomendacionRepository = $recomendacionRep;
        $this->usuarioRepository = $usuarioRep;
        $this->ofertaEmpleoRepository = $ofertaEmpleoRep;
    }

    //listar las recomendaciones
    #[Route(name: 'app_recomendacion', methods: ['GET'])]
    public function index(RecomendacionRepository $recomendacionRep): JsonResponse
    {
        $recomendaciones = $this->recomendacionRepository->findAll();
        $data = [];
        foreach ($recomendaciones as $recomendacion) {
            $data[] = [
                'id' => $recomendacion->getId(),
                'score' => $recomendacion->getScore(),
                'fecha' => $recomendacion->getFecha(),
                'id_usuario' => $recomendacion->getUsuario()->getId(),
                'id_oferta_empleo' => $recomendacion->getOfertaEmpleo()->getId(),
            ];
        }
        return new JsonResponse(['recomendaciones' => $data], Response::HTTP_OK);
    }

    //crear nueva recomendacion
    #[Route(name: 'app_recomencion_new', methods: ['POST'])]
    public function add(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent());

        //si datos vacios o no están los obligatorios, devolver mensaje de error
        if (!$data || !isset($data->score, $data->fecha)) {
            return new JsonResponse(['error' => 'No se pudo guardar el registro'], Response::HTTP_BAD_REQUEST);
        }

        //Obtenemos el curriculum al que hace referencia
        $usuario = $this->usuarioRepository->find($data->id_usuario);
        $oferta_empleo = $this->ofertaEmpleoRepository->find($data->id_oferta_empleo);

         $new_id = $this->recomendacionRepository->new(
            score: $data->score,
            fecha: new DateTime($data->fecha),
            usuario: $usuario,
            oferta_empleo: $oferta_empleo);

        return new JsonResponse([
            'status' => 'Usuario registrado correctamente',
            'curriculum' => [
                'score' => $data->score,
                'fecha' => $data->fecha,
                'id_usuario' => $data->id_usuario,
                'id_oferta_empleo' => $data->id_oferta_empleo,
            ]
        ], Response::HTTP_CREATED);
    }

    //mostrar datos de una recomendacion
    #[Route('/{id}', name: 'api_recomendacion_show', methods: ['GET'])]
    public function show(Recomendacion $recomendacion): JsonResponse
    {
        //usuario
        $data_usuario = [];
        $usuarios = $recomendacion->getUsuario();
        foreach ($usuarios as $usuario) {
            $data_usuario[] = [
                'id' => $usuario->getId(),
                'nombre' => $usuario->getNombre(),
                'apellidos' => $usuario->getCentro(),
                'email' => $usuario->getFechaInicio(),
                'telefono' => $usuario->getFechaFin(),
                'direccion' => $usuario->getDescripcion(),
                'ciudad' => $usuario->getCiudad(),
                'redes_sociales' => $usuario->setRedesSociales(),
                'foto' => $usuario->getFoto(),
                'resumen_perfil' => $usuario->getResumenPerfil(),
            ];
        }

        //oferta empleo
        $data_oferta_empleo = [];
        $oferta_empleos = $recomendacion->getOfertaEmpleo();
        foreach ($oferta_empleos as $oferta_empleo) {
            $data_oferta_empleo = [
                'id' => $oferta_empleo->getId(),
                'titulo' => $oferta_empleo->getTitulo(),
                'descripcion' => $oferta_empleo->getDescripcion(),
                'ubicacion' => $oferta_empleo->getUbicacion(),
                'tipo_contrato' => $oferta_empleo->getTipoContrato(),
                'salario' => $oferta_empleo->getSalario(),
                'fecha_publicacion' => $oferta_empleo->getFechaPublicacion(),
                'id_empresa' =>$oferta_empleo->getEmpresa()->getId(),
            ];
        }

        $data = [
            'id' => $recomendacion->getId(),
            'score' => $recomendacion->getScore(),
            'fecha' => $recomendacion->getFecha(),
            'usuario' => [$data_usuario],
            'oferta_empleo' => [$data_oferta_empleo]
        ];
        return new JsonResponse ($data, Response::HTTP_OK);
    }


    //editar un usuario
    #[Route('/{id}', name: 'api_recomendacion_edit', methods: ['PUT', 'PATCH'])]
    public  function edit(int $id, Request $request): JsonResponse
    {
        $recomendacion = $this->recomendacionRepository->find($id);
        $data = json_decode($request->getContent());

       //si datos vacios, devolver mensaje de error
        if (!$data) {
            return new JsonResponse(['error' => 'No se pudo editar el registro'], Response::HTTP_BAD_REQUEST);
        }

       if ($request->getMethod() == 'PUT')
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
       if (!empty($data->usuario_id)){
           $recomendacion->setUsuario($data->usuario);
       }
       if (!empty($data->oferta_id)){
           $recomendacion->setOfertaEmpleo($data->oferta);
       }
       $this->recomendacionRepository->save($recomendacion, true);
       return new JsonResponse(['status' => $mensaje], Response::HTTP_OK);
    }

    #[Route('/{id}', name: 'api_recomendacion_delete', methods: ['DELETE'])]
    public function remove(Recomendacion $recomendacion): JsonResponse
    {
        $score = $recomendacion->getScore();
        $this->recomendacionRepository->remove($recomendacion, flush: true);
        return new JsonResponse(['status' => 'recomendacion ' . $score . ' eliminada correctamente'], Response::HTTP_OK);
    }
}


//#[Route('/recomendacion', name: 'app_recomendacion')]
//    public function index(): Response
//{
//    return $this->render('recomendacion/index.html.twig', [
//        'controller_name' => 'RecomendacionController',
//    ]);
//}