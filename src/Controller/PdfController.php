<?php

namespace App\Controller;

use Knp\Snappy\Pdf;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PdfController extends AbstractController
{
    /**
     * @Route("/pdf/descarga", name="app_pdf")
     */
    public function descarga(Pdf $snappyPdf)
    {

        $fileName = 'mi-pdf.pdf';
        $htmlPdf = $this->renderView('pdf/descarga.html.twig', []);

        $footerHtmlPdf = $this->renderView('pdf/_footer.html.twig', []);

        return new Response(
            $snappyPdf->getOutputFromHtml($htmlPdf, [
                'footer-html' =>  $footerHtmlPdf
            ]),
            200,
            [
                'Content-Type' => 'application/pdf',
                'Content-disposition' => 'attachement; filename="' . $fileName . '"'
            ]
        );
    }
}
