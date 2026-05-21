<?php
/**
 * Generate font metrics for IPA Gothic font in AFM format
 * This script must be run during Docker build
 */

require_once __DIR__ . '/vendor/autoload.php';

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

    // Generate UFM file in AFM format
    $ufmFile = $fontDir . 'ipaexg.ufm';

    // Get character widths from font
    $glyphWidths = [];
    $unitsPerEm = $font->getData('head', 'unitsPerEm') ?: 1000;

    // Get horizontal metrics table
    $hmtx = $font->getData('hmtx');

    if ($hmtx && is_array($hmtx)) {
        foreach ($hmtx as $gid => $width) {
            if (is_numeric($width)) {
                // Convert to 1000 unit scale
                $scaledWidth = round(($width / $unitsPerEm) * 1000);
                $glyphWidths[$gid] = $scaledWidth;
            }
        }
    }

    // Create AFM format content
    $afm = "StartFontMetrics 4.1\n";
    $afm .= "Notice Converted by PHP-font-lib\n";
    $afm .= "Comment https://github.com/PhenX/php-font-lib\n";
    $afm .= "EncodingScheme FontSpecific\n";
    $afm .= "FontName {$fontName}\n";
    $afm .= "FontSubfamily {$fontSubfamily}\n";
    $afm .= "UniqueID {$fontName}\n";
    $afm .= "FullName {$fontFullName}\n";
    $afm .= "Version 1.0\n";
    $afm .= "Weight Medium\n";
    $afm .= "ItalicAngle 0\n";
    $afm .= "IsFixedPitch false\n";
    $afm .= "UnderlineThickness 50\n";
    $afm .= "UnderlinePosition -100\n";
    $afm .= "FontHeightOffset 0\n";
    $afm .= "Ascender 1000\n";
    $afm .= "Descender -200\n";
    $afm .= "FontBBox 0 -200 1000 800\n";

    // Write character metrics
    $charCount = count($glyphWidths);
    if ($charCount > 0) {
        $afm .= "StartCharMetrics {$charCount}\n";
        foreach ($glyphWidths as $gid => $width) {
            $afm .= "C {$gid} ; WX {$width} ; N g{$gid} ; B 0 0 {$width} 800 ;\n";
        }
        $afm .= "EndCharMetrics\n";
    } else {
        // Fallback to basic metrics
        $afm .= "StartCharMetrics 1\n";
        $afm .= "C 32 ; WX 500 ; N space ; B 0 0 0 0 ;\n";
        $afm .= "EndCharMetrics\n";
    }

    $afm .= "EndFontMetrics\n";

    // Write UFM file
    file_put_contents($ufmFile, $afm);

    if (file_exists($ufmFile)) {
        echo "UFM file created successfully: " . basename($ufmFile) . " (" . filesize($ufmFile) . " bytes)\n";
    } else {
        throw new Exception("Failed to create UFM file");
    }

    $font->close();

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    exit(1);
}

echo "Done!\n";
