<?php

namespace App\Controller;

use App\Repository\TareaRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class IndexController extends AbstractController
{
    const ELEMENTOS_POR_PAGINA = 10;

    /**
     * @Route(
     *  "/{pagina}",
     *  name="app_listado_tarea",
     *  defaults={
     *      "pagina": 1
     *  },
     *  requirements={
     *      "pagina"="\d+"
     *  },
     *  methods={
     *      "GET"
     *  }
     * )
     */
    public function index(int $pagina, TareaRepository $tareaRepository, TranslatorInterface $translator)
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        $tareas = $tareaRepository->buscarTodas($pagina, self::ELEMENTOS_POR_PAGINA);
        return $this->render('index/index.html.twig', [
            'tareas' => $tareas,
            'pagina' => $pagina,
            'texto_traducido_desde_controlador' => $translator->trans("Total de tareas: numTareas", [
                'numTareas' => count($tareas)
            ])
        ]);
    }
}
