<?php
class PDFGenerator {
    private $pdfContent;

    public function __construct() {
        $this->pdfContent = "%PDF-1.4\n";
    }

    public function addImage($imagePath) {
        list($width, $height) = getimagesize($imagePath);
        $imageData = file_get_contents($imagePath);

        $imageObject = "1 0 obj\n<< /Type /XObject /Subtype /Image /Width $width /Height $height /ColorSpace /DeviceRGB /BitsPerComponent 8 /Filter /DCTDecode /Length " . strlen($imageData) . " >>\nstream\n" . $imageData . "\nendstream\nendobj\n";
        $this->pdfContent .= $imageObject;

        $pageObject = "2 0 obj\n<< /Type /Page /Parent 3 0 R /Resources << /XObject << /I1 1 0 R >> >> /MediaBox [0 0 $width $height] /Contents 4 0 R >>\nendobj\n";
        $this->pdfContent .= $pageObject;

        $pageContent = "4 0 obj\n<< /Length 44 >>\nstream\nq\n$width 0 0 $height 0 0 cm\n/I1 Do\nQ\nendstream\nendobj\n";
        $this->pdfContent .= $pageContent;

        $catalogObject = "3 0 obj\n<< /Type /Pages /Kids [2 0 R] /Count 1 >>\nendobj\n";
        $this->pdfContent .= $catalogObject;

        $xref = "xref\n0 5\n0000000000 65535 f \n0000000010 00000 n \n0000000103 00000 n \n0000000207 00000 n \n0000000323 00000 n \n";
        $this->pdfContent .= $xref;

        $trailer = "trailer\n<< /Size 5 /Root 5 0 R /Info 6 0 R >>\nstartxref\n340\n%%EOF\n";
        $this->pdfContent .= $trailer;
    }

    public function output() {
        $rootObject = "5 0 obj\n<< /Type /Catalog /Pages 3 0 R >>\nendobj\n";
        $this->pdfContent .= $rootObject;

        $infoObject = "6 0 obj\n<< /Producer (PHP PDF Generator) >>\nendobj\n";
        $this->pdfContent .= $infoObject;

        $xrefPosition = strlen($this->pdfContent);
        $xref = "xref\n0 7\n0000000000 65535 f \n0000000010 00000 n \n0000000103 00000 n \n0000000207 00000 n \n0000000323 00000 n \n0000000420 00000 n \n0000000491 00000 n \n";
        $this->pdfContent .= $xref;

        $trailer = "trailer\n<< /Size 7 /Root 5 0 R /Info 6 0 R >>\nstartxref\n$xrefPosition\n%%EOF\n";
        $this->pdfContent .= $trailer;

        $tempPdfFile = tempnam(sys_get_temp_dir(), 'pdf') . '.pdf';
        file_put_contents($tempPdfFile, $this->pdfContent);

        return $tempPdfFile;
    }
}