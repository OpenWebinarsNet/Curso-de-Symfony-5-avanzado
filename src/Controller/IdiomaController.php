<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class IdiomaController extends AbstractController
{
    /**
     * @Route(
     *  "/idioma/{_locale}/{ruta}",
     *  name="app_idioma",
     *  defaults={
     *      "ruta": ""
     *  }
     *  )
     */
    public function index(string $ruta, Request $request)
    {
        $idiomaActual = $request->getSession()->get('_locale');
        $metodo = $request->getMethod();
        if ("POST" === $metodo) {
            $ruta = $request->request->get('ruta');
            return $this->redirectToRoute($ruta);
        }

        return $this->render('idioma/index.html.twig', [
            'ruta' => $ruta,
            'idioma_actual' => $idiomaActual
        ]);
    }
}
