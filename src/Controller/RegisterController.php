<?php

namespace App\Controller;

use App\Entity\Account;
use App\Entity\Empresa;
use App\Entity\Usuario;
use App\Repository\AccountRepository;
use App\Repository\EmpresaRepository;
use App\Repository\UsuarioRepository;
use App\Security\EmailVerifier;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;

#[Route('/api')]
final class RegisterController extends AbstractController
{
    private AccountRepository $accountRepository;
    private UsuarioRepository $usuarioRepository;
    private EmpresaRepository $empresaRepository;
    private EmailVerifier $emailVerifier;

    public function __construct(AccountRepository $accountRepository,
                                UsuarioRepository $usuarioRepository,
                                EmpresaRepository $empresaRepository,
                                EmailVerifier $emailVerifier)
    {
        $this->accountRepository = $accountRepository;
        $this->usuarioRepository = $usuarioRepository;
        $this->empresaRepository = $empresaRepository;
        $this->emailVerifier = $emailVerifier;
    }

    #[Route('/profile', name: 'app_profile', methods: ['GET'])]
    public function profile(): JsonResponse
    {
        //obtener cuenta del usuario autenticado
        $account = $this->getUser();

        //cuenta no autenticada
        if (!$account) {
            return new JsonResponse(['error' => 'No autenticado'], response::HTTP_UNAUTHORIZED);
        }

        $data = [
            "id" => $account->getId(),
            "email" => $account->getEmail(),
            "roles" => $account->getRoles()
        ];
        $usuario = $this->usuarioRepository->findOneBy(["account" => $account]);
        $empresa = $this->empresaRepository->findOneBy(["account" => $account]);

        //cuenta de usuario
        if ($usuario){
            $data["profile_id"] = $usuario->getId();
        }
        elseif ($empresa) {
            $data["profile_id"] = $empresa->getId();
        }
        else{
            return new JsonResponse(['error' => 'Perfil no encontrado'], Response::HTTP_NOT_FOUND);
        }

        //devolver datos perfil usuario
        return new JsonResponse(['info_perfil' => $data], Response::HTTP_OK);

    }

    #[Route('/register', name: 'app_register_new', methods: ['POST'])]
    public function add(Request $request, UserPasswordHasherInterface $hasher): JsonResponse
    {
        $data = json_decode($request->getContent());

        // Validación básica
        if (!$data || !isset($data->email, $data->password, $data->nombre, $data->tipo)) {
            return new JsonResponse(['error' => 'No se pudo guardar el registro'], Response::HTTP_BAD_REQUEST);
        }

        $em = $this->accountRepository->getEntityManager();

        // Crear account
        $account = new Account();
        $account->setEmail($data->email);
        //hashear password (https://symfony.com/doc/current/security/passwords.html#hashing-the-password)
        $account->setPassword($hasher->hashPassword($account, $data->password));
        $account->setIsVerified(false);
        $account->setRoles($data->tipo === 'EMPRESA' ? ['ROLE_EMPRESA'] : ['ROLE_USER']);

        $em->persist($account);

        // Crear usuario o empresa
        if ($data->tipo === 'EMPRESA') {
            $empresa = new Empresa();
            $empresa->setNombre($data->nombre);
            $empresa->setEmail($data->email);
            $empresa->setAccount($account);
            $em->persist($empresa);
        } else {
            $usuario = new Usuario();
            $usuario->setNombre($data->nombre);
            $usuario->setEmail($data->email);
            $usuario->setAccount($account);
            $em->persist($usuario);
        }

        // Persistir y guardar en bbdd
        $em->flush();


        // enviar correo de configuramcion
        /*$this->emailVerifier->sendEmailConfirmation('app_verify_email', $account,
            (new TemplatedEmail())
                ->from(new Address('fernandasar1289@gmail.com', 'Talent Match Intelligence'))
                ->to((string) $account->getEmail())
                ->subject('Por favor, confirma tu Email')
                ->htmlTemplate('registration/confirmation_email.html.twig')
        );*/
        $this->emailVerifier->sendEmailConfirmation(
            'app_verify_email',
            $account,
            (new TemplatedEmail())
                ->from(new Address('fernandasar1289@gmail.com', 'Talent Match Intelligence'))
                ->to((string) $account->getEmail())
                ->subject('Por favor, confirma tu Email')
                ->htmlTemplate('registration/confirmation_email.html.twig'),
            ['id' => $account->getId()] // CLAVE
        );



        // Devolver respuesta
        return new JsonResponse([
            'status' => $data->tipo === 'EMPRESA' ? 'Empresa registrada correctamente' : 'Usuario registrado correctamente',
            'email' => $data->email,
            'nombre' => $data->nombre,
            'tipo' => $data->tipo,
            'id' => $account->getId()
        ], Response::HTTP_CREATED);
    }


    #[Route('/register/verify/email', name: 'app_verify_email', methods: ['GET'])]
    public function verifyUserEmail(Request $request): Response
    {
        // 1. Obtener el ID de la URL
        $id = $request->query->get('id');

        if (!$id) {
            // Redirigir al frontend con error si no hay ID
            $mensaje = "ID de usuario no proporcionado";
            return $this->redirect('http://localhost:4200/email-verification?error=' . urlencode($mensaje));
        }

        // 2. Buscar la cuenta en la base de datos
        $account = $this->accountRepository->find($id);

        if (!$account) {
            $mensaje = "Usuario no encontrado";
            return $this->redirect('http://localhost:4200/email-verification?error=' . urlencode($mensaje));
        }

        // 3. Validar la firma del email
        try {
            // Pasamos el objeto $account encontrado, NO el del token/sesión (porque en las APIs no sueles estar logueado aún)
            $this->emailVerifier->handleEmailConfirmation($request, $account);
        } catch (VerifyEmailExceptionInterface $exception) {
            // Si la firma expiró o es manipulada
            return $this->redirect(
                'http://localhost:4200/email-verification?error=' . urlencode($exception->getReason())
            );
        }

        // 4. Éxito: Redirigir al login del frontend
        return $this->redirect('http://localhost:4200/email-verification');
    }


}
