<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use App\Security\Mailer;
use App\Service\FileUploader;
use Nzo\UrlEncryptorBundle\Encryptor\Encryptor;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegistrationController extends AbstractController
{

    /**
     * @Route("/register", name="app_register")
     */
    public function register(string $emailAplicacion, Mailer $mailer, FileUploader $fileUploader, Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
            $imagen = $form->get('imagen')->getData();
            if ($imagen) {
                $imagenFileName = $fileUploader->upload($imagen);
                $user->setImagen($imagenFileName);
            }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            $mailer->enviarEmailRegistroUsuario($user);
            // $this->addFlash('success', 'Usuario registrado correctamente, revise su correo electrónico.');

            return $this->redirectToRoute('app_login');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/registro/activacion/{token}", name="app_activar_usuario")
     */
    public function activarCuentaUsuario(string $token, UserRepository $userRepository, Encryptor $encryptor)
    {
        $tokenJson = $encryptor->decrypt($token);
        $datosToken = (array)json_decode($tokenJson);
        $fechaActual = new \DateTime();
        $fechaExpiracion = new \DateTime($datosToken['fechaExpiracion']);
        $idUsuario = $datosToken['id'];
        if ($fechaActual > $fechaExpiracion) {
            throw $this->createNotFoundException();
        }

        $usuario = $userRepository->findOneById($idUsuario);
        $usuario->setIsVerified(true);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->flush($usuario);
        $this->addFlash('success', 'Usuario activado correctamente');
        return $this->redirectToRoute('app_login');
    }
}
