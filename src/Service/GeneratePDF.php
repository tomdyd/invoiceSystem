<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Attribute\Route;
use tFPDF;


class GeneratePDF
{
    public function generatePdf(SessionInterface $session): tFPDF
    {
        $content = $session->get('cart', []);
        $quantity = $session->get('quantity', []);
        $person = $session->get('person', []);
        $nett = 0;
        $brute = 0;

        $pdf = new tFPDF();
        $pdf->AddPage();
        $pdf->AddFont('roboto', '', 'Roboto-Regular.ttf', true);
        $pdf->SetFont('roboto', '', 12);

        $pageWidth = $pdf->GetPageWidth();
        $rightMargin = 10;
        $topMargin = 10;

        $xPosition = $pageWidth - $rightMargin - 60;
        $yPosition = $topMargin; // Górny margines

        $pdf->SetXY($xPosition, $yPosition);
        $pdf->Cell(40, 5,'Imię: ' . $person->getName() . '', 0, 1, 'L');

        $pdf->SetX($xPosition);
        $pdf->Cell(40, 5,'Nazwisko: ' . $person->getSurname() . '', 0, 1, 'L');

        $pdf->SetX($xPosition);
        $pdf->Cell(40, 5,'Adres: ' . $person->getAddress() . '', 0, 1, 'L');

        $pdf->SetX($xPosition);
        $pdf->Cell(40, 5,'Email: ' . $person->getEmail() . '', 0, 1, 'L');

        $pdf->SetX($xPosition);
        $pdf->Cell(40, 5,'Tel: ' . $person->getPhone() . '', 0, 1, 'L');

        foreach ($content as $book)
        {
            $pdf->Cell(40, 5, 'Nazwa: ' . $book->getName() . '', 0, 1, 'L');
            $pdf->Cell(40, 5, 'Autor: ' . $book->getAuthor() . '', 0, 1, 'L');
            $pdf->Cell(40, 5, 'Ilość: ' . $quantity[$book->getId()] . '', 0, 1, 'L');
            $nett += $book->getPrice() * $quantity[$book->getId()];
            $pdf->Cell(40, 5, 'Cena netto: ' . $book->getPrice() * $quantity[$book->getId()] . ' zł', 0, 1, 'L');
            $brute += $book->getPrice() * $quantity[$book->getId()] * 1.23;
            $pdf->Cell(40, 5, 'Cena brutto: ' . $book->getPrice() * $quantity[$book->getId()] * 1.23 . ' zł', 0, 1, 'L');
            $pdf->Ln(5);
        }

        $pdf->Cell(40, 10, 'Całkowita kwota netto do zapłaty: ' . $nett . ' zł', 0, 1, 'L');
        $pdf->Cell(40, 10, 'Całkowita kwota brutto do zapłaty: ' . $brute . ' zł', 0, 1, 'L');

        $pdfContent = $pdf->Output('S'); // Output PDF jako string

        // Utworzenie nowego obiektu PdfDocument
        $pdfDocument = new PdfDocument();
        $pdfDocument->setFilename('example.pdf');
        $pdfDocument->setContent($pdfContent);

        return $pdf;
    }
}