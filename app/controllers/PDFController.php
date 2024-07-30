<?php
require_once './models/PDFGenerator.php';

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class PDFController {
    public function generatePdf(Request $request, Response $response, $args) {

        $pdfGenerator = new \PDFGenerator();

        $imagePath = './archivos/ImagenesEmpresa/lacomandaimagen.jpeg';
        $pdfGenerator->addImage($imagePath);

        $pdfFile = $pdfGenerator->output();

        $pdfContent = file_get_contents($pdfFile);
        $response->getBody()->write($pdfContent);

        unlink($pdfFile);

        return $response->withHeader('Content-Type', 'application/pdf')
                        ->withHeader('Content-Disposition', 'attachment; filename="documento.pdf"');
    }
}