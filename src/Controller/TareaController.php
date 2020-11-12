<?php

namespace App\Controller;

use App\Entity\Tarea;
use App\Form\TareaType;
use App\Repository\TareaRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/tarea")
 */
class TareaController extends AbstractController
{
    /**
     * @Route("/listado", name="tarea_index", methods={"GET"})
     */
    public function index(TareaRepository $tareaRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        return $this->render('tarea/index.html.twig', [
            'tareas' => $tareaRepository->findBy([], ['creadoEn' => 'DESC']),
        ]);
    }

    /**
     * @Route("/nueva", name="tarea_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {       
        $tarea = new Tarea();
        $this->denyAccessUnlessGranted('crear', $tarea);

        $tarea->setUsuario($this->getUser());
        $form = $this->createForm(TareaType::class, $tarea);
        $form->handleRequest($request);
        

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($tarea);
            $entityManager->flush();

            return $this->redirectToRoute('app_listado_tarea');
        }

        return $this->render('tarea/new.html.twig', [
            'tarea' => $tarea,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="tarea_show", methods={"GET"})
     */
    public function show(Tarea $tarea): Response
    {
        // $this->denyAccessUnlessGranted('ROLE_USER');
        $this->denyAccessUnlessGranted('ver', $tarea);
        return $this->render('tarea/show.html.twig', [
            'tarea' => $tarea,
        ]);
    }

    /**
     * @Route("/{id}/editar", name="tarea_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Tarea $tarea): Response
    {
        // $this->denyAccessUnlessGranted('ROLE_USER');
        $this->denyAccessUnlessGranted('editar', $tarea);
        
        $form = $this->createForm(TareaType::class, $tarea);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('app_listado_tarea');
        }

        return $this->render('tarea/edit.html.twig', [
            'tarea' => $tarea,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="tarea_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Tarea $tarea): Response
    {
        $this->denyAccessUnlessGranted('eliminar', $tarea);

        if ($this->isCsrfTokenValid('delete' . $tarea->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($tarea);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_listado_tarea');
    }

    /**
     * @Route("/{id}", name="finalizar_tarea", methods={"POST"})
     */
    public function finalizar(Tarea $tarea, Request $request): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        if ($request->isXmlHttpRequest()) {
            $entityManager = $this->getDoctrine()->getManager();
            $tarea->setFinalizada(!$tarea->getFinalizada());
            $entityManager->flush();
            return $this->json([
                'finalizada' => $tarea->getFinalizada()
            ]);
        }

        throw $this->createNotFoundException();
    }
}
