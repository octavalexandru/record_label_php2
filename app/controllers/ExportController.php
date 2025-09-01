<?php
require_once __DIR__ . '/../vendor/autoload.php';

class ExportController {
    public static function pdf() {
        require_once __DIR__ . '/../utils/PdfExport.php';
        PdfExport::downloadPdf();
    }

    public static function excel() {
        require_once __DIR__ . '/../utils/ExcelExport.php';
        ExcelExport::downloadExcel();
    }
}
