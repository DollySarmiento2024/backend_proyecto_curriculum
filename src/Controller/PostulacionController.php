<?php

namespace App\Controller;

use App\Entity\Postulacion;
use App\Repository\PostulacionRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

    #[Route('/postulacion')]
    final class PostulacionController extends AbstractController
    {
       private PostulacionRepository $postulacionRepository;

       public function __construct(PostulacionRepository $postulacionRep)
       {
            $this->postulacionRepository = $postulacionRep;
       }

        #[Route(name: 'app-postulacion')]
        public function index(PostulacionRepository $postulacionRep): JsonResponse
        {
            $postulaciones = $postulacionRep->findAll();
            $data = [];
            foreach ($postulaciones as $postulacion) {
                $data[] = [
                    'id' => $postulacion->getId(),
                    'fecha' =>$postulacion->getFecha(),
                    'carta_presentacion' =>$postulacion->getCartaPresentacion(),
                    'estado' =>$postulacion->getEstado(),
                    'usuario' =>$postulacion->getUsuario()->getId(),
                    'oferta' =>$postulacion->getOferta()->getId(),
                ];
            }
            return new JsonResponse(['postulaciones' => $data], Response::HTTP_OK);
        }

        #[Route('/new', name: 'app_postulacion_new', methods: ['POST'])]

        public function add(Request $request): JsonResponse
        {
            $data = json_decode($request->getContent());

            //si datos vacios o no están los obligatorios, devolver mensaje de error
            if(!$data ){
                return new JsonResponse(['error' => 'No se pudo guardar el registro'], Response::HTTP_BAD_REQUEST);
            }
            $fecha_actual = date("Y-m-d H:i:s");;
            $this->postulacionRepository->new(
                fecha: $fecha_actual,
                carta_presentacion: $data->carta_presentacion,
                estado: $data->estado,
                usuario: $data->usuario_id,
                oferta: $data->oferta_id);

            return new JsonResponse([
                'status' => 'Postulacion registrada correctamente',
                'postulacion' => [
                    'fecha' => $fecha_actual,
                    'carta_presentacion' => $data->carta_presentacion,
                    'estado' => $data->estado,
                    'usuario' => $data->usuario_id,
                    'oferta' => $data->oferta_id,
                ]
           ],  Response::HTTP_CREATED);
        }

      #[Route('/{id}', name: 'app_postulacion_show', methods: ['GET'])]

      public function show(Postulacion $postulacion): JsonResponse
      {
        $data = [
            'id' => $postulacion->getId(),
            'fecha' =>$postulacion->getFecha(),
            'carta_presentacion' =>$postulacion->getCartaPresentacion(),
            'estado' =>$postulacion->getEstado(),
            'usuario' =>$postulacion->getUsuario()->getId(),
            'oferta'=>$postulacion->getOferta()->getId(),
        ];
        return new JsonResponse($data, Response::HTTP_OK);
      }

    #[Route('/edit/{id}', name: 'app_postulacion_edit', methods: ['PUT', 'PATCH'])]
    public function edit($id, Request $request): JsonResponse
        {
            $postulacion = $this->postulacionesRepository->find($id);
            $data = json_decode($request->getContent());
            if ($_SERVER['REQUEST_METHOD'] === 'PUT')
            {
                $mensaje = 'Postulacion actualizado correctamente';
            } else {
                $mensaje = 'Postulacion actualizada parcialmente';
            }

            if (!empty($data->carta_presentacion)) {
                $postulacion->setCartaPresentacion($data->carta_presentacion);
            }
            if (!empty($data->estado)) {
                $postulacion->setEstado($data->estado);
            }
            if (!empty($data->usuario_id)) {
                $postulacion->setUsuarioId($data->usuario_id);
            }
            if (!empty($data->oferta_id)) {
                $postulacion->setOfertaId($data->oferta_id);
            }
            $this->postulacionesRepository->save($postulacion, true);
            return new JsonResponse(['status' => $mensaje], Response::HTTP_CREATED);
        }

        #[Route('/delete/{id}', name: 'app_postulacion_delete', methods: ['DELETE'])]

        public function remove(Postulacion $postulacion): JsonResponse
        {
            $fecha = $postulacion->getFecha();
            $this->postulacionesRepository->remove($postulacion, flush: true);
            return new JsonResponse(['status' => 'postulacion' . $fecha . 'Postulacion eliminado correctamente'], Response::HTTP_OK);

        }


    }

//    #[Route('/postulacion', name: 'app_postulacion')]
//    public function index(): Response
//    {
//        return $this->render('postulacion/index.html.twig', [
//            'controller_name' => 'PostulacionController',
//        ]);
//    }