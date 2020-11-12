<?php

namespace App\Controller;

use Nzo\UrlEncryptorBundle\Encryptor\Encryptor;
use Nzo\UrlEncryptorBundle\Annotations\ParamDecryptor;
use Nzo\UrlEncryptorBundle\Annotations\ParamEncryptor;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class EncriptarController extends AbstractController
{

    /**
     * @Route("/encriptar/{texto}", name="app_encriptar")
     * @ParamEncryptor(params={"texto"})
     */
    public function encriptar(string $texto)
    {
        return $this->json([
            'url' => $this->generateUrl('app_desencriptar', [
                'textoEncriptado' => $texto
            ], UrlGeneratorInterface::ABSOLUTE_URL)
        ]);
    }

    /**
     * @Route("/desencriptar/{textoEncriptado}", name="app_desencriptar")
     */
    public function desencriptar(string $textoEncriptado, Encryptor $encryptor)
    {
        $textoDesencriptado = $encryptor->decrypt($textoEncriptado);
        return $this->json([
            'texto' => $textoDesencriptado
        ]);
    }

    /**
     * @Route("/encriptar-twig/{texto}", name="app_encriptar_twig")
     */
    public function encriptarTwig(string $texto)
    {
        return $this->render('encriptar/index.html.twig', [
            'texto' => $texto
        ]);
    }

    /**
     * @Route("/desencriptar-twig/{texto}", name="app_desencriptar_twig")
     * @ParamDecryptor(params={"texto"})
     */
    public function desencriptarTwig(string $texto)
    {
        return $this->json([
            'texto' => $texto
        ]);
    }
}
