<?php
require __DIR__ . '/../vendor/autoload.php';

use Smalot\PdfParser\Parser;

header('Content-Type: application/json'); // Ensure response is JSON

$file = $_GET['file'] ?? '';

if (!$file) {
    echo json_encode(['success' => false, 'error' => 'No file provided.']);
    exit;
}

$pdfFilePath = __DIR__ . "/../pdf/" . basename($file); // Secure file path

if (!file_exists($pdfFilePath)) {
    echo json_encode(['success' => false, 'error' => 'File not found.']);
    exit;
}

try {
    $parser = new Parser();
    $pdf = $parser->parseFile($pdfFilePath);
    $text = $pdf->getText();

    echo json_encode(['success' => true, 'text' => $text]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>