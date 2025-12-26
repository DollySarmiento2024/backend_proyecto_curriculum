<?php

namespace App\Controller;

use App\Entity\Empresa;
use App\Repository\EmpresaRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/api/empresa')]
#[IsGranted('ROLE_EMPRESA')]
final class EmpresaController extends AbstractController
{
    private EmpresaRepository $empresaRepository;

    public function __construct(EmpresaRepository $empresaRep)
    {
        $this->empresaRepository = $empresaRep;
    }

    //Listar todas las empresas
    #[Route(name: 'api_empresa', methods: ['GET'])]
    public function index(EmpresaRepository $empresaRep): JsonResponse
    {
        $empresas = $this->empresaRepository->findAll();
        $data = [];
        foreach ($empresas as $empresa) {
            $data[] = [
                'id' => $empresa->getId(),
                'nombre' => $empresa->getNombre(),
                'email' => $empresa->getEmail(),
                'telefono' => $empresa->getTelefono(),
                'direccion' => $empresa->getDireccion(),
                'ciudad' => $empresa->getCiudad(),
                'sector' => $empresa->getSector(),
                'descripcion' => $empresa->getDescripcion(),
                'logo' => $empresa->getLogo(),
                'sitio_web' => $empresa->getSitioWeb(),
                'redes_sociales' => $empresa->getRedesSociales()
            ];
        }
        return new JsonResponse(['empresas' => $data], Response::HTTP_OK );
    }

    //crear nueva empresa
    #[Route(name: 'api_empresa_new', methods: ['POST'])]
    public function add(Request $request): JsonResponse
    {
        $data = Json_decode($request->getContent());

        //si datos vacios o no están los obligatorios, devolver mensaje de error
        if (!$data || !isset($data->nombre, $data->email)) {
            return new JsonResponse(['error' => 'No se pudo guardar el registro'], Response::HTTP_BAD_REQUEST);
        }

        $new_id = $this->empresaRepository->new(
            nombre: $data->nombre,
            email: $data->email,
            telefono: $data->telefono,
            direccion: $data->direccion,
            ciudad: $data->ciudad,
            sector: $data->sector,
            descripcion: $data->descripcion,
            logo: $data->logo,
            sitio_web: $data->sitio_web,
            redes_sociales: $data->redes_sociales);

    return new JsonResponse([
        'status' => 'Empresa registrada correctamente',
        'empresa' => [
            'id' => $new_id,
            'nombre' => $data->nombre,
            'email' => $data->email,
            'telefono' => $data->telefono,
            'direccion' => $data->direccion,
            'ciudad' => $data->ciudad,
            'sector' => $data->sector,
            'descripcion' => $data->descripcion,
            'logo' => $data->logo,
            'sitio_web' => $data->sitio_web,
            'redes_sociales' => $data->redes_sociales,
        ]
    ], Response::HTTP_CREATED);
  }

    //Mostrar datos de una empresa
    #[Route('/{id}', name: 'api_empresa_show', methods: ['GET'])]
    public function show(Empresa $empresa): JsonResponse
    {
        $data = [
           'id' => $empresa->getId(),
           'nombre' => $empresa->getNombre(),
           'email' => $empresa->getEmail(),
           'telefono' => $empresa->getTelefono(),
           'direccion' => $empresa->getDireccion(),
           'ciudad' => $empresa->getCiudad(),
           'sector' => $empresa->getSector(),
           'descripcion' => $empresa->getDescripcion(),
           'logo' => $empresa->getLogo(),
           'sitio_web' => $empresa->getSitioWeb(),
            'redes_sociales' => $empresa->getRedesSociales()
        ];
        return new JsonResponse($data, Response::HTTP_OK);
    }

    //Editar una empresa
    #[Route('/{id}', name: 'api_empresa_edit', methods: ['PUT', 'PATCH'])]
    public function edit(int $id, Request $request): JsonResponse
    {
        $empresa = $this->empresaRepository->find($id);
        $data = Json_decode($request->getContent());

        //si datos vacios, devolver mensaje de error
        if(!$data){
            return new JsonResponse(['error' => 'No se pudo editar el registro'], Response::HTTP_BAD_REQUEST);
        }

         if ($request->getMethod() === 'PUT')
         {
             $mensaje = 'Empresa actualizada correctamente';
         } else {
             $mensaje = 'Empresa actualizada parcialmente';
         }
         if(!empty($data->nombre)){
             $empresa->setNombre($data->nombre);
         }
         if(!empty($data->email)){
             $empresa->setEmail($data->email);
         }
         if(!empty($data->telefono)){
             $empresa->setTelefono($data->telefono);
         }
         if(!empty($data->direccion)){
             $empresa->setDireccion($data->direccion);
         }
         if(!empty($data->ciudad)){
             $empresa->setCiudad($data->ciudad);
         }
          if (!empty($data->sector)) {
             $empresa->setSector($data->sector);
         }
         if (!empty($data->descripcion)) {
             $empresa->setDescripcion($data->descripcion);
         }
         if (!empty($data->logo)) {
             $empresa->setLogo($data->logo);
         }
         if (!empty($data->sitio_web)) {
             $empresa->setSitioWeb($data->sitio_web);
         }
         if (!empty($data->redes_sociales)){
             $empresa->setRedesSociales($data->redes_sociales);
         }
         $this->empresaRepository->save($empresa, true);
         return new JsonResponse(['status' => $mensaje], Response::HTTP_OK);
    }

    #[Route('/{id}', name: 'api_empresa_delete', methods: ['DELETE'])]
    public function remove(Empresa $empresa):jsonResponse
    {
        $nombre = $empresa->getNombre();
        $this->empresaRepository->remove($empresa, true);
        return new JsonResponse(['status' => 'empresa ' . $nombre . ' eliminada correctamente'], Response::HTTP_OK);
    }
}




/* #[Route('/empresa', name: 'app_empresa')]
 public function index(): Response
 {
     return $this->render('empresa/index.html.twig', [
         'controller_name' => 'EmpresaController',
     ]);
 }*/