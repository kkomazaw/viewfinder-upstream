<?php
/**
 * Generate font metrics for IPA Gothic font using Font_Metrics utility
 * This script must be run during Docker build
 */

require_once __DIR__ . '/vendor/autoload.php';

use Dompdf\FontMetrics;
use FontLib\Font;

$fontDir = __DIR__ . '/vendor/dompdf/dompdf/lib/fonts/';
$fontFile = 'ipaexg.ttf';
$fontPath = $fontDir . $fontFile;

// Check if font file exists
if (!file_exists($fontPath)) {
    die("Error: Font file not found: {$fontPath}\n");
}

echo "Generating font metrics for {$fontFile}...\n";

try {
    // Load font file
    $font = Font::load($fontPath);
    $font->parse();

    // Get font data
    $fontName = $font->getFontName();
    $fontFullName = $font->getFontFullName();
    $fontSubfamily = $font->getFontSubfamily();

    echo "Font Name: {$fontName}\n";
    echo "Full Name: {$fontFullName}\n";
    echo "Subfamily: {$fontSubfamily}\n";

    // Generate UFM file manually
    $ufmFile = $fontDir . 'ipaexg.ufm';
    $data = [
        'codeToName' => [],
        'isUnicode' => true,
        'FontName' => $fontName,
        'FullName' => $fontFullName,
        'FamilyName' => $font->getFontFamily(),
        'Weight' => $font->getFontWeight(),
        'ItalicAngle' => 0,
        'IsFixedPitch' => false,
        'CharacterSet' => 'Unicode',
        'FontBBox' => $font->getData('head', 'xMin') . ' ' . $font->getData('head', 'yMin') . ' ' . $font->getData('head', 'xMax') . ' ' . $font->getData('head', 'yMax'),
        'UnderlinePosition' => -100,
        'UnderlineThickness' => 50,
        'Version' => '1.0',
        'EncodingScheme' => 'FontSpecific',
        'CapHeight' => 800,
        'XHeight' => 600,
        'Ascender' => 1000,
        'Descender' => -200,
        'StdHW' => 50,
        'StdVW' => 50,
        'StartCharMetrics' => 0,
    ];

    // Write UFM file
    file_put_contents($ufmFile, serialize($data));

    if (file_exists($ufmFile)) {
        echo "UFM file created successfully: " . basename($ufmFile) . "\n";
    } else {
        throw new Exception("Failed to create UFM file");
    }

    $font->close();

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    exit(1);
}

echo "Done!\n";
