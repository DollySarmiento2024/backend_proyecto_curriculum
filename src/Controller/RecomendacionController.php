<?php

namespace App\Controller;

use App\Entity\Recomendacion;
use App\Repository\OfertaEmpleoRepository;
use App\Repository\RecomendacionRepository;
use App\Repository\UsuarioRepository;
use App\Service\EvaluadorCandidatoService;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/api/recomendacion')]
#[IsGranted('ROLE_USER')]
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
    #[Route(name: 'api_recomendacion', methods: ['GET'])]
    public function index(RecomendacionRepository $recomendacionRep): JsonResponse
    {
        $recomendaciones = $this->recomendacionRepository->findAll();
        $data = [];
        foreach ($recomendaciones as $recomendacion) {
            $data[] = [
                'id' => $recomendacion->getId(),
                'score' => $recomendacion->getScore(),
                'fecha' => $recomendacion->getFecha()->format("Y-m-d"),
                'id_usuario' => $recomendacion->getUsuario()->getId(),
                'id_oferta_empleo' => $recomendacion->getOfertaEmpleo()->getId(),
            ];
        }
        return new JsonResponse(['recomendaciones' => $data], Response::HTTP_OK);
    }

    //crear nueva recomendacion
    #[Route(name: 'api_recomencion_new', methods: ['POST'])]
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

        $fecha_actual = new \DateTime();//date("Y-m-d H:i:s");
        $new_id = $this->recomendacionRepository->new(
            score: $data->score,
            puntos_fuertes: $data->puntos_fuertes ?? [],
            puntos_debiles: $data->puntos_debiles ?? [],
            conclusion: $data->conclusion ?? "",
            fecha: new DateTime($data->fecha),
            usuario: $usuario,
            oferta_empleo: $oferta_empleo);


        return new JsonResponse([
            'status' => 'Recomendación registrada correctamente',
            'recomendacion' => [
                'score' => $data->score,
                'puntos_fuertes' => $data->puntos_fuertes,
                'puntos_debiles' => $data->puntos_debiles,
                'conclusion' => $data->conclusion,
                'fecha' => $data->fecha,
                'id_usuario' => $data->id_usuario,
                'id_oferta_empleo' => $data->id_oferta_empleo,
            ]
        ], Response::HTTP_CREATED);
    }

    //generar recomendaciones para el usuario mediante ia
    #[Route('/generar_ai/{id_usuario}', name: 'api_recomendacion_generar_ai', methods: ['POST'])]
    public function generarAi(int $id_usuario, EvaluadorCandidatoService $iaService): JsonResponse
    {
        //1. obtener los datos del USUARIO
        $usuario = $this->usuarioRepository->find($id_usuario);

        //Formaciones del usuario
        $data_formaciones = [];
        $formaciones = $usuario->getFormaciones();
        foreach ($formaciones as $formacion) {
            $data_formaciones[] = [
                'titulo' => $formacion->getTitulo(),
                'centro' =>$formacion->getCentro(),
                'fecha_inicio' =>$formacion->getFechaInicio()->format("Y-m-d"),
                'fecha_fin' =>$formacion->getFechaFin()->format("Y-m-d"),
                'descripcion' =>$formacion->getDescripcion()
            ];
        }

        //Experiencias del usuario
        $data_experiencias = [];
        $experiencias = $usuario->getExperiencias();
        foreach ($experiencias as $experiencia) {
            $data_experiencias[] = [
                'puesto' =>$experiencia->getPuesto(),
                'empresa' =>$experiencia->getEmpresa(),
                'fecha_inicio' => $experiencia->getFechaInicio()->format("Y-m-d"),
                'fecha_fin' => $experiencia->getFechaFin()->format("Y-m-d"),
                'descripcion' => $experiencia->getDescripcion(),
            ];
        }

        //Habilidad del usuario
        $data_habilidades = [];
        $habilidades = $usuario->getHabilidades();
        foreach ($habilidades as $habilidad) {
            $data_habilidades[] = [
                'nombre' => $habilidad->getNombre(),
                'nivel' => $habilidad->getNivel(),
                'descripcion' => $habilidad->getDescripcion(),
             ];
        }

        //Idioma del usuario
        $data_idioma = [];
        $idiomas = $usuario->getIdiomas();
        foreach ($idiomas as $idioma){
            $data_idioma[] = [
                'nombre' => $idioma->getNombre(),
                'nivel'=> $idioma->getNivel(),
            ];
        }

        //Conocimiento del usuario
        $data_conocimiento = [];
        $conocimientos = $usuario->getConocimientos();
        foreach ($conocimientos as $conocimiento){
            $data_conocimiento[] = [
                'nombre' =>$conocimiento->getNombre(),
                'nivel'=> $conocimiento->getNivel(),
                'descripcion' =>$conocimiento->getDescripcion(),
            ];
        }

        $data_usuario = [
            'resumen_perfil' => $usuario->getResumenPerfil(),
            'formaciones' => $data_formaciones ,
            'experiencias' => $data_experiencias ,
            'habilidades' => $data_habilidades,
            'idiomas' => $data_idioma,
            'conocimientos' => $data_conocimiento
        ];

        $ofertas_empleo = $this->ofertaEmpleoRepository->findAll();

        //2. buscamos las recomendaciones antiguas y las borramos
        $recomendaciones_antiguas = $this->recomendacionRepository->findBy(['usuario' => $usuario]);

        foreach ($recomendaciones_antiguas as $recomendacion) {
            $this->recomendacionRepository->remove($recomendacion, flush: true);
        }

        $cont_recomendaciones = 0;
        $errores = [];
        $resultdos_ai = [];
        $raw = '';

        //3. recorrer todas las ofertas para hacer match con ia y guardamos la recomendación
        foreach ($ofertas_empleo as $oferta_empleo) {
            try{
                $data_oferta_empleo = [
                    'titulo' => $oferta_empleo->getTitulo(),
                    'descripcion' => $oferta_empleo->getDescripcion(),
                    'ubicacion' => $oferta_empleo->getUbicacion(),
                    'tipo_contrato' => $oferta_empleo->getTipoContrato(),
                    'salario' => $oferta_empleo->getSalario(),
                    'fecha_publicacion' => $oferta_empleo->getFechaPublicacion()->format("Y-m-d"),
                ];

                //calculo de match con ia
                $raw = $iaService->evaluate($data_usuario, $data_oferta_empleo);

                // Limpiar Markdown
                $clean = trim(str_replace(['```json', '```'], '', $raw));
                $result_ai = json_decode($clean, true);

                $resultdos_ai[] = $result_ai;

                // Esperar 1.5 segundos entre peticiones para no saturar la API de Gemini
                usleep(1500000);

                if ($result_ai && isset($result_ai['score']))
                {
                    //guardar la recomendacion
                    $fecha_actual = new \DateTime();//date("Y-m-d H:i:s");
                    $new_id = $this->recomendacionRepository->new(
                        score: (float) $result_ai['score'],
                        puntos_fuertes: $result_ai['puntosFuertes'] ?? [],
                        puntos_debiles: $result_ai['puntosDebiles'] ?? [],
                        conclusion: $result_ai['conclusion'] ?? "",
                        fecha: $fecha_actual,
                        usuario: $usuario,
                        oferta_empleo: $oferta_empleo);

                    $cont_recomendaciones++;

                }
            }
            catch(\Exception $e){
                // Si una oferta falla (error de la IA), seguimos con la siguiente
                $errores[] = $e->getMessage();
                continue;
            }

        }
        return new JsonResponse([
            'status' => 'Recomendaciones IA generadas correctamente',
            'errores' => $errores,
            'resultados_ai' => $resultdos_ai,
            'raw' => $raw,
            'num_recomendaciones' => $cont_recomendaciones
        ], Response::HTTP_OK);
    }

    #[Route('/usuario/{id}', name: 'api_recomendacion_usuario', methods: ['GET'])]
    public function indexByUsuario(int $id): JsonResponse
    {
        $usuario = $this->usuarioRepository->find($id);
        $recomendaciones = $this->recomendacionRepository->findBy(['usuario' => $usuario]);
        $data = [];
        foreach ($recomendaciones as $recomendacion) {
            //Oferta empleo
            $oferta_empleo = $recomendacion->getOfertaEmpleo();
            $data_oferta_empleo = [
                'id' => $oferta_empleo->getId(),
                'titulo' => $oferta_empleo->getTitulo(),
                'descripcion' => $oferta_empleo->getDescripcion(),
                'ubicacion' => $oferta_empleo->getUbicacion(),
                'tipo_contrato' => $oferta_empleo->getTipoContrato(),
                'salario' => $oferta_empleo->getSalario(),
                'fecha_publicacion' => $oferta_empleo->getFechaPublicacion()->format("Y-m-d"),
                'id_empresa' =>$oferta_empleo->getEmpresa()->getId(),
            ];

            $data[] = [
                'id' => $recomendacion->getId(),
                'score' => $recomendacion->getScore(),
                'fecha' => $recomendacion->getFecha()->format("Y-m-d"),
                'id_usuario' => $recomendacion->getUsuario()->getId(),
                'id_oferta_empleo' =>$recomendacion->getOfertaEmpleo()->getId(),
                'oferta_empleo' => $data_oferta_empleo
            ];
        }
        return new JsonResponse(['recomendaciones' => $data], Response::HTTP_OK);
    }

     //mostrar datos de una recomendacion
    #[Route('/{id}', name: 'api_recomendacion_show', methods: ['GET'])]
    public function show(Recomendacion $recomendacion): JsonResponse
    {
        //usuario
        $usuario = $recomendacion->getUsuario();

        $data_usuario[] = [
                'id' => $usuario->getId(),
                'nombre' => $usuario->getNombre(),
                'email' => $usuario->getEmail(),
                'telefono' => $usuario->getTelefono(),
                'direccion' => $usuario->getDireccion(),
                'ciudad' => $usuario->getCiudad(),
                'redes_sociales' => $usuario->getRedesSociales(),
                'foto' => $usuario->getFoto(),
                'resumen_perfil' => $usuario->getResumenPerfil(),
        ];


        //oferta empleo
        $oferta_empleo = $recomendacion->getOfertaEmpleo();
        $data_oferta_empleo = [
                'id' => $oferta_empleo->getId(),
                'titulo' => $oferta_empleo->getTitulo(),
                'descripcion' => $oferta_empleo->getDescripcion(),
                'ubicacion' => $oferta_empleo->getUbicacion(),
                'tipo_contrato' => $oferta_empleo->getTipoContrato(),
                'salario' => $oferta_empleo->getSalario(),
                'fecha_publicacion' => $oferta_empleo->getFechaPublicacion()->format("Y-m-d"),
                'id_empresa' =>$oferta_empleo->getEmpresa()->getId(),
        ];

        $data = [
            'id' => $recomendacion->getId(),
            'score' => $recomendacion->getScore(),
            'fecha' => $recomendacion->getFecha()->format("Y-m-d"),
            'usuario' => $data_usuario,
            'oferta_empleo' => $data_oferta_empleo
        ];
        return new JsonResponse ($data, Response::HTTP_OK);
    }


    //editar un usuario
    #[Route('/{id}', name: 'api_recomendacion_edit', methods: ['PUT', 'PATCH'])]
    public  function edit(int $id, Request $request): JsonResponse
    {
        $recomendacion = $this->recomendacionRepository->find($id);
        $data = Json_decode($request->getContent());

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