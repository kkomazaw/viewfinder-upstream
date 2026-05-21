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

    // Get character widths from font using FontLib's getUnicodeCharMap
    $glyphWidths = [];
    $unitsPerEm = $font->getData('head', 'unitsPerEm') ?: 1000;

    echo "Units per EM: {$unitsPerEm}\n";

    // Try to get Unicode character map
    $charMap = null;
    try {
        // Method 1: Try getUnicodeCharMap if available
        if (method_exists($font, 'getUnicodeCharMap')) {
            $charMap = $font->getUnicodeCharMap();
            echo "Got Unicode char map using getUnicodeCharMap(): " . count($charMap) . " characters\n";
        }
    } catch (Exception $e) {
        echo "Note: getUnicodeCharMap failed: " . $e->getMessage() . "\n";
    }

    // Get horizontal metrics table
    $hmtx = $font->getData('hmtx');

    if ($charMap && is_array($charMap) && $hmtx && is_array($hmtx)) {
        // Use the character map we already got
        echo "Extracting character widths from char map and hmtx...\n";

        foreach ($charMap as $unicode => $gid) {
            if (isset($hmtx[$gid])) {
                $metrics = $hmtx[$gid];

                // Handle both array format [advanceWidth, lsb] and simple numeric format
                $advanceWidth = is_array($metrics) ? $metrics[0] : $metrics;
                $scaledWidth = round(($advanceWidth / $unitsPerEm) * 1000);
                $glyphWidths[$unicode] = $scaledWidth;
            }
        }
        echo "Extracted " . count($glyphWidths) . " character widths from char map\n";
    } elseif ($hmtx && is_array($hmtx)) {
        // Fallback: try to get cmap manually
        echo "Attempting to extract character metrics from hmtx table...\n";

        $cmap = $font->getData('cmap', 'subtable');

        if ($cmap && is_array($cmap)) {
            echo "Found cmap subtable with " . count($cmap) . " entries\n";

            foreach ($cmap as $unicode => $gid) {
                if (isset($hmtx[$gid])) {
                    $metrics = $hmtx[$gid];

                    // Handle both array format [advanceWidth, lsb] and simple numeric format
                    $advanceWidth = is_array($metrics) ? $metrics[0] : $metrics;
                    $scaledWidth = round(($advanceWidth / $unitsPerEm) * 1000);
                    $glyphWidths[$unicode] = $scaledWidth;
                }
            }
            echo "Extracted " . count($glyphWidths) . " character widths from cmap/hmtx\n";
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
        foreach ($glyphWidths as $unicode => $width) {
            // Use Unicode value as character code
            // For Japanese characters, typical width is around 1000 for full-width
            // Bounding box: llx lly urx ury (set to reasonable defaults)
            $afm .= "C {$unicode} ; WX {$width} ; N uni" . dechex($unicode) . " ; B 0 -200 {$width} 800 ;\n";
        }
        $afm .= "EndCharMetrics\n";

        echo "Generated metrics for {$charCount} characters\n";

        // Show sample character widths for verification
        $sampleChars = array_slice($glyphWidths, 0, 10, true);
        echo "Sample character widths:\n";
        foreach ($sampleChars as $unicode => $width) {
            $char = mb_chr($unicode, 'UTF-8');
            $hex = dechex($unicode);
            echo "  U+{$hex} ({$char}): {$width} units\n";
        }
    } else {
        // Fallback to basic metrics
        echo "Warning: No character widths extracted, using fallback\n";
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
