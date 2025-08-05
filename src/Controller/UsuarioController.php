<?php

namespace App\Controller;
use App\Entity\Usuario;
use App\Repository\UsuarioRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/usuario')]
final class UsuarioController extends AbstractController
{
    private UsuarioRepository $usuarioRepository;

    public function __construct(UsuarioRepository $usuarioRep)
    {
        $this->usuarioRepository = $usuarioRep;
    }

    //listar los usuarios
    #[Route(name: 'api_usuario',  methods: ['GET'])]
    public function index(UsuarioRepository $usuarioRep): JsonResponse
    {
        $usuarios = $this->usuarioRepository->findAll();
        $data = [];
        foreach ($usuarios as $usuario) {
            $data[] = [
                'id' => $usuario->getId(),
                'nombre' => $usuario->getNombre(),
                'apellidos' => $usuario->getApellidos(),
                'email' => $usuario->getEmail(),
                'telefono' => $usuario->getTelefono(),
                'direccion' => $usuario->getDireccion(),
                'ciudad'=> $usuario->getCiudad(),
                'redes_sociales' => $usuario->getRedesSociales(),
                'foto' => $usuario->getFoto(),
                'resumen_perfil' => $usuario->getResumenPerfil(),
            ];
        }
        return new JsonResponse(['usuarios' => $data], Response::HTTP_OK);
    }

    //crear nuevo usuario
    #[Route(name: 'api_usuario_new', methods: ['POST'])]
    public function add(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent());

        //si datos vacios o no están los obligatorios, devolver mensaje de error
        if (!$data || !isset($data->nombre, $data->apellidos, $data->email)){
            return  new JsonResponse(['error' => 'No se pudo guardar el registro'], Response::HTTP_BAD_REQUEST);
        }

        $new_id = $this->usuarioRepository->new(
            nombre: $data->nombre,
            apellidos: $data->apellidos ,
            email: $data->email,
            telefono: $data->telefono,
            direccion: $data->direccion,
            ciudad: $data->ciudad,
            redes_sociales: $data->redes_sociales,
            foto: $data->foto,
            resumen_perfil: $data->resumen_perfil);

        return new JsonResponse([
            'status' => 'Usuario registrado correctamente',
            'usuario' => [
                'nombre' => $data->nombre,
                'apellidos' => $data->apellidos,
                'email' => $data->email,
                'telefono' => $data->telefono,
                'direccion' => $data->direccion,
                'ciudad' =>$data->ciudad,
                'redes_sociales' => $data->redes_sociales,
                'foto' => $data->foto,
                'resumen_perfil' => $data->resumen_perfil,
                'id_usuario' => $data->id_usuario
            ]
        ], Response::HTTP_CREATED);
    }

    //mostrar datos de un usuario
    #[Route('/{id}', name: 'api_usuario_show', methods: ['GET'])]
    public function show( Usuario $usuario): JsonResponse
    {
        //Formaciones del usuario
        $data_formaciones = [];
        $formaciones = $usuario->getFormaciones();
        foreach ($formaciones as $formacion) {
            $data_formaciones[] = [
                'id' => $formacion->getId(),
                'titulo' => $formacion->getTitulo(),
                'centro' =>$formacion->getCentro(),
                'fecha_inicio' =>$formacion->getFechaInicio(),
                'fecha_fin' =>$formacion->getFechaFin(),
                'descripcion' =>$formacion->getDescripcion(),
                'id_usuario' =>$formacion->getUsuario()->getId(),
            ];
        }

        //Experiencias del usuario
        $data_experiencias = [];
        $experiencias = $usuario->getExperiencias();
        foreach ($experiencias as $experiencia) {
            $data_experiencias[] = [
                'id' => $experiencia->getId(),
                'puesto' =>$experiencia->getPuesto(),
                'empresa' =>$experiencia->getEmpresa(),
                'fecha_inicio' => $experiencia->getFechaInicio(),
                'fecha_fin' => $experiencia->getFechaFin(),
                'descripcion' => $experiencia->getDescripcion(),
                'id_usuario' => $experiencia->getUsuario()->getId(),
            ];
        }

        //Habilidad del usuario
        $data_habilidades = [];
        $habilidades = $usuario->getHabilidades();
        foreach ($habilidades as $habilidad) {
            $data_habilidades[] = [
             'id' => $habilidad->getId(),
             'nombre' => $habilidad->getNombre(),
             'nivel' => $habilidad->getNivel(),
             'descripcion' => $habilidad->getDescripcion(),
              'id_usuario' => $habilidad->getUsuario()->getId(),
            ];
        }

        //Idioma del usuario
        $data_idioma = [];
        $idiomas = $usuario->getIdiomas();
        foreach ($idiomas as $idioma){
            $data_idioma[] = [
                'id' => $idioma->getId(),
                'nombre' => $idioma->getNombre(),
                'nivel'=> $idioma->getNivel(),
                'id_usuario'=> $idioma->getUsuario()->getId(),
            ];
        }

        //Conocimiento del usuario
        $data_conocimiento = [];
        $conocimientos = $usuario->getConocimientos();
        foreach ($conocimientos as $conocimiento){
            $data_conocimiento[] = [
                'id' => $conocimiento->getId(),
                'nombre' =>$conocimiento->getNombre(),
                'nivel'=> $conocimiento->getNivel(),
                'descripcion' =>$conocimiento->getDescripcion(),
                'id_usuario'=> $conocimiento->getUsuario()->getId(),
            ];
        }

        $data = [
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
            'formaciones' => [ $data_formaciones ],
            'experiencia' => [ $data_experiencias ],
            'habilidad' => [$data_habilidades],
            'idioma' => [$data_idioma],
            'conocimiento' => [$data_conocimiento]
        ];
        return new JsonResponse($data, Response::HTTP_OK);
    }

    //editar un usuario
    #[Route('/{id}', name: 'api_usuario_edit', methods: ['PUT', 'PATCH'])]
    public  function edit(int $id, Request $request): JsonResponse
    {
        $usuario = $this->usuarioRepository->find($id);
        $data = json_decode($request->getContent());

        //si datos vacios, devolver mensaje de error
        if (!$data){
            return  new JsonResponse(['error' => 'No se pudo editar el registro'], Response::HTTP_BAD_REQUEST);
        }

        if ($request->getMethod() == 'PUT')
        {
            $mensaje = 'Usuario actualizado correctamente';
        } else {
            $mensaje = 'Usuario actualizado parcialmente';
        }

        if (!empty($data->nombre)) {
            $usuario->setNombre($data->nombre);
        }
        if (!empty($data->apellidos)) {
            $usuario->setApellidos($data->apellidos);
        }
        if (!empty($data->email)) {
            $usuario->setEmail($data->email);
        }
        if (!empty($data->telefono)) {
            $usuario->setTelefono($data->telefono);
        }
        if (!empty($data->direccion)) {
            $usuario->setDireccion($data->direccion);
        }
        if (!empty($data->ciudad)){
            $usuario->setCiudad($data->ciudad);
        }
        if (!empty($data->redes_sociales)) {
            $usuario->setRedesSociales($data->redes_sociales);
        }
        if (!empty($data->foto)) {
            $usuario->setFoto($data->foto);
        }
        if (!empty($data->resumenPerfil)) {
            $usuario->setResumenPerfil($data->resumen_perfil);
        }
        $this->usuarioRepository->save($usuario, true);
        return new JsonResponse(['status' => $mensaje], Response::HTTP_OK);
    }

    #[Route('/{id}', name: 'api_usuario_delete', methods: ['DELETE'])]
    public function  remove(Usuario $usuario): JsonResponse
    {
        $nombre = $usuario->getNombre();
        $this->usuarioRepository->remove($usuario, true);
        return new JsonResponse(['status' => 'usuario ' . $nombre . ' eliminado correctamente'], Response::HTTP_OK);
    }
}
