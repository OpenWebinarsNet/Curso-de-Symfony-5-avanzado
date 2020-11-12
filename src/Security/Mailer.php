<?php

namespace App\Security;

use App\Entity\User;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Nzo\UrlEncryptorBundle\Encryptor\Encryptor;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class Mailer
{

    private $emailAplicacion;
    private $emailManager;
    private $router;
    private $encryptor;

    public function __construct(string $emailAplicacion, Encryptor $encryptor, UrlGeneratorInterface $router, MailerInterface $emailManager)
    {
        $this->emailAplicacion = $emailAplicacion;
        $this->emailManager = $emailManager;
        $this->encryptor = $encryptor;
        $this->router = $router;
    }

    public function enviarEmailRegistroUsuario(User $user)
    {

        $this->enviarEmail($user->getEmail(), 'Email de confirmación!', 'registration/email_confirmacion.html.twig', [
            'user' => $user,
            'url_activar_usuario' => $this->generarUrlActivacionUsuario($user),
        ]);
    }

    public function generarUrlActivacionUsuario(User $user)
    {
        $fechaHoraExpiracion = new \DateTime();
        $fechaHoraExpiracion->modify('+1 day');

        $datos = [
            'id' => $user->getId(),
            'fechaExpiracion' => $fechaHoraExpiracion->format('Y-m-d H:m:s')
        ];

        $token = $this->encryptor->encrypt(json_encode($datos));

        return $this->router->generate('app_activar_usuario', [
            'token' => $token
        ], UrlGeneratorInterface::ABSOLUTE_URL);
    }

    public function enviarEmail(String $para, string $titulo, string $template, array $params)
    {
        $email = (new TemplatedEmail())
            ->from($this->emailAplicacion)
            ->to($para)
            ->subject($titulo)
            ->htmlTemplate($template)
            ->context($params);

        $this->emailManager->send($email);
    }
}
