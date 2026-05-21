<?php
/**
 * Generate font metrics for IPA Gothic font
 * This script must be run during Docker build
 */

require_once __DIR__ . '/vendor/autoload.php';

use Dompdf\FontMetrics;
use Dompdf\Options;

$fontDir = __DIR__ . '/vendor/dompdf/dompdf/lib/fonts/';
$fontFile = 'ipaexg.ttf';
$fontPath = $fontDir . $fontFile;

// Check if font file exists
if (!file_exists($fontPath)) {
    die("Error: Font file not found: {$fontPath}\n");
}

echo "Generating font metrics for {$fontFile}...\n";

// Create options
$options = new Options();
$options->set('fontDir', $fontDir);
$options->set('fontCache', $fontDir);
$options->set('isRemoteEnabled', false);

// Create FontMetrics instance
$fontMetrics = new FontMetrics($options);

// Get font info to trigger metrics generation
try {
    $font = $fontMetrics->getFont('ipaexg', 'normal');
    echo "Font metrics generated successfully!\n";
    echo "Font: {$font}\n";
} catch (Exception $e) {
    echo "Error generating font metrics: " . $e->getMessage() . "\n";
    exit(1);
}

// Verify .ufm file was created
$ufmFiles = glob($fontDir . 'ipaexg*.ufm*');
if (count($ufmFiles) > 0) {
    echo "Created UFM files:\n";
    foreach ($ufmFiles as $file) {
        echo "  - " . basename($file) . "\n";
    }
} else {
    echo "Warning: No .ufm files were created\n";
}

echo "Done!\n";
