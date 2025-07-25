<?php

namespace App\Controller;

use App\Entity\Empresa;
use App\Repository\EmpresaRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/empresa')]
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
        $empresas = $empresaRep->findAll();
        $data = [];
        foreach ($empresas as $empresa) {
            $data[] = [
                'id' => $empresa->getId(),
                'nombre' => $empresa->getNombre(),
                'email' => $empresa->getEmail(),
                'telefono' => $empresa->getTelefono(),
                'direccion' => $empresa->getDireccion(),
                'ciudad' => $empresa->getCiudad(),
                'pais' => $empresa->getPais(),
                'sector' => $empresa->getSector(),
                'descripcion' => $empresa->getDescripcion(),
                'logo' => $empresa->getLogo(),
                'sitio_web' => $empresa->getSitioWeb(),
            ];
        }
        return new JsonResponse(['empresas' => $data], Response::HTTP_OK );
    }

    //crear nueva empresa
    #[Route('/new', name: 'api_empresa_new', methods: ['POST'])]
    public function add(Request $request): JsonResponse
    {
        $data = Json_decode($request->getContent(), true);

        //si datos vacios o no están los obligatorios, devolver mensaje de error
        if (!$data || !isset($data->nombre, $data->email)) {
            return new JsonResponse(['error' => 'No se pudo guardar el registro'], Response::HTTP_BAD_REQUEST);
        }
        $this->empresaRepository->new(
            nombre: $data->nombre,
            email: $data->email,
            telefono: $data->telefono,
            direccion: $data->direccion,
            ciudad: $data->ciudad,
            pais: $data->pais,
            sector: $data->sector,
            descripcion: $data->descripcion,
            logo: $data->logo,
            sitio_web: $data->sitio_web);

    return new JsonResponse([
        'status' => 'Empresa registrada correctamente',
        'empresa' => [
            'nombre' => $data->nombre,
            'email' => $data->email,
            'telefono' => $data->telefono,
            'direccion' => $data->direccion,
            'ciudad' => $data->ciudad,
            'pais' => $data->pais,
            'sector' => $data->sector,
            'descripcion' => $data->descripcion,
            'logo' => $data->logo,
            'sitio_web' => $data->sitio_web,
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
           'pais' => $empresa->getPais(),
           'sector' => $empresa->getSector(),
           'descripcion' => $empresa->getDescripcion(),
           'logo' => $empresa->getLogo(),
           'sitio_web' => $empresa->getSitioWeb(),
        ];
        return new JsonResponse($data, Response::HTTP_OK);
    }

    //Editar una empresa
    #[Route('/edit/{id}', name: 'api_empresa_edit', methods: ['PUT', 'PATCH'])]
    public function edit(int $id, Request $request): JsonResponse
    {
        $empresa = $this->empresaRepository->find($id);
        $data = Json_decode($request->getContent());
         if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
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
         if (!empty($data->pais)) {
             $empresa->setPais($data->pais);
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
         $this->empresaRepository->save($empresa, true);
         return new JsonResponse(['status' => $mensaje], Response::HTTP_CREATED);
    }

    #[Route('/delete/{id}', name: 'api_empresa_delete', methods: ['DELETE'])]
    public function remove(Empresa $empresa):jsonResponse
    {
        $nombre = $empresa->getNombre();
        $this->empresaRepository->remove($empresa, true);
        return new JsonResponse(['status' => 'empresa' . $nombre . 'Empresa eliminada correctamente'], Response::HTTP_OK);
    }
}




/* #[Route('/empresa', name: 'app_empresa')]
 public function index(): Response
 {
     return $this->render('empresa/index.html.twig', [
         'controller_name' => 'EmpresaController',
     ]);
 }*/