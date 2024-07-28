<?php

namespace App\Controller;

use App\Entity\PdfDocument;
use App\Service\GeneratePDF;
use Doctrine\ORM\EntityManagerInterface;
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
    public function index(SessionInterface $session, EntityManagerInterface $entityManager): Response
    {

        $pdf = $this->pdfGenerator->generatePdf($session);

        // Utworzenie nowego obiektu PdfDocument
        $pdfDocument = new PdfDocument();
        $pdfContent = $pdf->Output('S'); // Output PDF jako string
        $pdfDocument->setFilename('Invoice');
        $pdfDocument->setContent($pdfContent);

        $entityManager->persist($pdfDocument);
        $entityManager->flush();

        return new Response($pdf->output('S'), Response::HTTP_OK, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="document.pdf"'
        ]);
    }
}
