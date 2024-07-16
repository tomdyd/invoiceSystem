<?php

namespace App\Controller;

use App\Service\GeneratePDF;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Attribute\Route;

class GeneratePDFController extends AbstractController
{
    private GeneratePDF $pdfGenerator;

    public function __construct(GeneratePDF $pdfGenerator)
    {
        $this->pdfGenerator = $pdfGenerator;
    }

    #[Route('/generate-pdf', name: 'app_generate_pdf')]
    public function index(SessionInterface $session): Response
    {

        $pdf = $this->pdfGenerator->generatePdf($session);

        return new Response($pdf->output('S'), Response::HTTP_OK, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="document.pdf"'
        ]);
    }
}
