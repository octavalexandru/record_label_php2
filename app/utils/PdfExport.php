<?php
use TCPDF;

class PdfExport {
    public static function downloadPdf() {
        $pdf = new TCPDF();
        $pdf->AddPage();
        $pdf->SetFont('helvetica', '', 12);
        $pdf->Write(0, "Sample PDF export from your Record Label App");
        $pdf->Output('export.pdf', 'D');
    }
}
