<?php

namespace App\Controller;

use App\Entity\PdfDocument;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ReadPDFController extends AbstractController
{
    #[Route('/read-pdf/{id}', name: 'app_read_pdf')]
    public function index(EntityManagerInterface $entityManager, $id): Response
    {
        $files = $entityManager->getRepository(PdfDocument::class)->findAll();

        if (!$files) {
            throw $this->createNotFoundException('No PDF files found.');
        }

        $file = $files[$id];
        $pdfContent = stream_get_contents($file->getContent());
        $filename = $file->getFilename();

        return new Response($pdfContent, Response::HTTP_OK, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $filename . '"'
        ]);
    }
}
