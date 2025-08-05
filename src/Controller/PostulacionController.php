<?php

namespace App\Controller;

use App\Entity\Postulacion;
use App\Repository\OfertaEmpleoRepository;
use App\Repository\PostulacionRepository;
use App\Repository\UsuarioRepository;
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
       private UsuarioRepository $usuarioRepository;
       private OfertaEmpleoRepository $ofertaEmpleoRepository;


        public function __construct(PostulacionRepository $postulacionRep, UsuarioRepository $usuarioRep, OfertaEmpleoRepository $ofertaEmpleoRep )
       {
            $this->postulacionRepository = $postulacionRep;
            $this->usuarioRepository = $usuarioRep;
            $this->ofertaEmpleoRepository = $ofertaEmpleoRep;
       }

        #[Route(name: 'app_postulacion')]
        public function index(PostulacionRepository $postulacionRep): JsonResponse
        {
            $postulaciones = $this->postulacionRepository->findAll();
            $data = [];
            foreach ($postulaciones as $postulacion) {
                $data[] = [
                    'id' => $postulacion->getId(),
                    'fecha' =>$postulacion->getFecha(),
                    'carta_presentacion' =>$postulacion->getCartaPresentacion(),
                    'estado' =>$postulacion->getEstado(),
                    'score' =>$postulacion->getScore(),
                    'id_usuario' =>$postulacion->getUsuario()->getId(),
                    'id_oferta_empleo' =>$postulacion->getOfertaEmpleo()->getId(),
                ];
            }
            return new JsonResponse(['postulaciones' => $data], Response::HTTP_OK);
        }

        #[Route(name: 'app_postulacion_new', methods: ['POST'])]
        public function add(Request $request): JsonResponse
        {
            $data = json_decode($request->getContent());

            //si datos vacios o no están los obligatorios, devolver mensaje de error
            if(!$data || !isset($data->fecha, $data->score, $data->estado)){
                return new JsonResponse(['error' => 'No se pudo guardar el registro'], Response::HTTP_BAD_REQUEST);
            }

            //Obtenemos el curriculum al que hace referencia
            $usuario = $this->usuarioRepository->find($data->id_usuario);
            $oferta_empleo = $this->ofertaEmpleoRepository->find($data->id_oferta_empleo);

            $fecha_actual = new \DateTimeImmutable();//date("Y-m-d H:i:s");
            $new_id = $this->postulacionRepository->new(
                fecha: new \DateTimeImmutable($data->fecha),
                carta_presentacion: $data->carta_presentacion,
                estado: $data->estado,
                score: $data->score,
                usuario: $usuario,
                oferta_empleo: $oferta_empleo);

            return new JsonResponse([
                'status' => 'Postulacion registrada correctamente',
                'postulacion' => [
                    'id' => $new_id,
                    'fecha' => $fecha_actual,
                    'carta_presentacion' => $data->carta_presentacion,
                    'estado' => $data->estado,
                    'score' => $data->score,
                    'id_usuario' => $data->id_usuario,
                    'id_oferta_empleo' => $data->id_oferta_empleo,
                ]
           ],  Response::HTTP_CREATED);
        }

      #[Route('/{id}', name: 'app_postulacion_show', methods: ['GET'])]
      public function show(Postulacion $postulacion): JsonResponse
      {
          //Usuario
          $data_usuario = [];
          $usuarios = $postulacion->getUsuario();
          foreach ($usuarios as $usuario ) {
              $data_usuario[] = [
                  'id' => $usuario->getId(),
                  'nombre' => $usuario->getNombre(),
                  'apellidos' => $usuario->getApellidos(),
                  'email' => $usuario->getEmail(),
                  'telefono' => $usuario->getTelefono(),
                  'direccion' => $usuario->getDireccion(),
                  'ciudad' => $usuario->getCiudad(),
                  'redes_sociales' => $usuario->getRedesSociales(),
                  'foto' => $usuario->getFoto(),
                  'resumen_perfil' => $usuario->getResumenPerfil(),
              ];
          }

          //Oferta empleo
          $data_oferta_empleo = [];
          $oferta_empleos = $postulacion->getOfertaEmpleo();
          foreach ($oferta_empleos as $oferta_empleo) {
              $data_oferta_empleo[] = [
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
                'id' => $postulacion->getId(),
                'fecha' =>$postulacion->getFecha(),
                'carta_presentacion' =>$postulacion->getCartaPresentacion(),
                'estado' =>$postulacion->getEstado(),
                'score' => $postulacion->getScore(),
                'usuario' =>[$data_usuario],
                'oferta_empleo'=>[$data_oferta_empleo]
            ];
          return new JsonResponse($data, Response::HTTP_OK);
      }

        #[Route('/{id}', name: 'app_postulacion_edit', methods: ['PUT', 'PATCH'])]
        public function edit($id, Request $request): JsonResponse
        {
            $postulacion = $this->postulacionRepository->find($id);
            $data = json_decode($request->getContent());

            //si datos vacios, devolver mensaje de error
            if (!$data){
                return  new JsonResponse(['error' => 'No se pudo editar el registro'], Response::HTTP_BAD_REQUEST);
            }

            if ($request->getMethod() == 'PUT')
            {
                $mensaje = 'Postulacion actualizado correctamente';
            } else {
                $mensaje = 'Postulacion actualizada parcialmente';
            }

            if (!empty($data->fecha)) {
                $postulacion->setFecha($data->fecha);
            }
            if (!empty($data->carta_presentacion)) {
                $postulacion->setCartaPresentacion($data->carta_presentacion);
            }
            if (!empty($data->estado)) {
                $postulacion->setEstado($data->estado);
            }
            if (!empty($data->score)) {
                $postulacion->setScore($data->score);
            }

            if (!empty($data->usuario_id)) {
                $postulacion->setUsuario($data->usuario);
            }
            if (!empty($data->oferta_id)) {
                $postulacion->setOfertaEmpleo($data->oferta_empleo);
            }
            $this->postulacionRepository->save($postulacion, true);
            return new JsonResponse(['status' => $mensaje], Response::HTTP_OK);
        }

        #[Route('/{id}', name: 'app_postulacion_delete', methods: ['DELETE'])]
        public function remove(Postulacion $postulacion): JsonResponse
        {
            $id = $postulacion->getId();
            $this->postulacionRepository->remove($postulacion, flush: true);
            return new JsonResponse(['status' => 'postulacion con id ' . $id . ' eliminada correctamente'], Response::HTTP_OK);
        }
   }

//    #[Route('/postulacion', name: 'app_postulacion')]
//    public function index(): Response
//    {
//        return $this->render('postulacion/index.html.twig', [
//            'controller_name' => 'PostulacionController',
//        ]);
//    }