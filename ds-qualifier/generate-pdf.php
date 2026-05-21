<?php
/**
 * PDF Generation for Digital Sovereignty Readiness Assessment Results
 */

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../i18n/I18n.php';

use Dompdf\Dompdf;
use Dompdf\Options;

// Start session to retrieve assessment data
session_start();

// Check if we have assessment data in session
if (!isset($_SESSION['assessment_data']) || empty($_SESSION['assessment_data'])) {
    die(__('error.no_assessment_data', [], 'No assessment data found. Please complete the assessment first.'));
}

// Get assessment data from session
$assessmentData = $_SESSION['assessment_data'];

// Load questions configuration
$questions = require_once 'config.php';

// Load profiles and get selected profile
$profiles = require_once 'profiles.php';
$selectedProfile = isset($assessmentData['profile']) ? $assessmentData['profile'] : 'balanced';

// Validate profile exists
if (!isset($profiles[$selectedProfile])) {
    $selectedProfile = 'balanced';
}

$profileData = $profiles[$selectedProfile];

// Handle custom weights if custom profile is selected
if ($selectedProfile === 'custom') {
    $domainWeights = [];
    foreach ($questions as $domainName => $domainData) {
        $paramName = 'custom_weight_' . str_replace(' ', '_', $domainName);
        if (isset($assessmentData[$paramName])) {
            $weight = floatval($assessmentData[$paramName]);
            $domainWeights[$domainName] = max(1.0, min(2.0, $weight));
        } else {
            $domainWeights[$domainName] = 1.0;
        }
    }
} else {
    $domainWeights = $profileData['weights'];
}

// Initialize scoring arrays (same logic as results.php)
$totalScore = 0;
$weightedScore = 0;
$maxScore = 21;
$domainScores = [];
$domainMaxScores = [];
$domainWeightedScores = [];
$domainResponses = [];
$unknownQuestions = [];

// Initialize domain scores
foreach ($questions as $domainName => $domainData) {
    $domainScores[$domainName] = 0;
    $domainMaxScores[$domainName] = count($domainData['questions']);
    $domainResponses[$domainName] = [];
}

// Calculate scores - EXACT same logic as results.php
foreach ($assessmentData as $key => $value) {
    // Match question IDs (ds1, ts1, os1, etc.)
    if (preg_match('/^(ds|ts|os|as|oss|eo|ms)\d+$/', $key)) {
        // Find which domain this question belongs to
        foreach ($questions as $domainName => $domainData) {
            foreach ($domainData['questions'] as $question) {
                if ($question['id'] === $key) {
                    // Handle "Don't Know" responses
                    if ($value === 'unknown') {
                        $unknownQuestions[] = [
                            'domain' => $domainName,
                            'domain_key' => $domainData['name_key'],
                            'question_key' => $question['text_key'],
                            'tooltip_key' => $question['tooltip_key'] ?? ''
                        ];
                    } else {
                        $intValue = intval($value);
                        $totalScore += $intValue;
                        $domainScores[$domainName] += $intValue;
                        // Track "Yes" responses (value > 0)
                        if ($intValue > 0) {
                            $domainResponses[$domainName][] = $question['text_key'];
                        }
                    }
                    break 2;
                }
            }
        }
    }
}

// Calculate weighted scores per domain
$totalWeight = 0;
$weightedSum = 0;

foreach ($domainScores as $domainName => $score) {
    $maxForDomain = $domainMaxScores[$domainName];
    $weight = $domainWeights[$domainName] ?? 1.0;

    // Calculate percentage for this domain (0-100%)
    $domainPercentage = $maxForDomain > 0 ? ($score / $maxForDomain) : 0;

    // Apply weight
    $weightedDomainScore = $domainPercentage * $weight;
    $domainWeightedScores[$domainName] = $weightedDomainScore;

    $weightedSum += $weightedDomainScore;
    $totalWeight += $weight;
}

// Normalize weighted score to 0-21 scale
$weightedScore = $totalWeight > 0 ? ($weightedSum / $totalWeight) * 21 : 0;

// Determine maturity level based on WEIGHTED score (CMMI 5-level system)
if ($weightedScore <= 4.2) {
    $maturityLevelKey = 'maturity.initial';
    $maturityColor = '#c9190b';
    $maturityIcon = '🔴';
    $recommendationDetailKey = 'maturity.initial.description';
} elseif ($weightedScore <= 8.4) {
    $maturityLevelKey = 'maturity.managed';
    $maturityColor = '#ec7a08';
    $maturityIcon = '🟠';
    $recommendationDetailKey = 'maturity.managed.description';
} elseif ($weightedScore <= 12.6) {
    $maturityLevelKey = 'maturity.defined';
    $maturityColor = '#ffc107';
    $maturityIcon = '🟡';
    $recommendationDetailKey = 'maturity.defined.description';
} elseif ($weightedScore <= 16.8) {
    $maturityLevelKey = 'maturity.quantitative';
    $maturityColor = '#8bc34a';
    $maturityIcon = '🟢';
    $recommendationDetailKey = 'maturity.quantitative.description';
} else {
    $maturityLevelKey = 'maturity.optimizing';
    $maturityColor = '#2aaa04';
    $maturityIcon = '🚀';
    $recommendationDetailKey = 'maturity.optimizing.description';
}

// Get translated strings
$maturityLevel = __($maturityLevelKey);
$recommendationDetail = __($recommendationDetailKey);

// Calculate percentage based on weighted score
$scorePercentage = round(($weightedScore / $maxScore) * 100);
$assessmentDate = date('F j, Y \a\t g:i A');

// Determine font based on locale
$locale = getLocale();
$fontFamily = 'Arial'; // Default for English
$cssFontFamily = 'Arial, sans-serif';
$wordBreakStyle = 'normal';
$lineBreakStyle = 'auto';

if ($locale === 'ja') {
    // Use IPA Ex Gothic for Japanese (better Dompdf compatibility)
    // Use only ipaexg without fallback to ensure all text uses Japanese font
    $fontFamily = 'ipaexg';
    $cssFontFamily = 'ipaexg';
    // For Japanese: allow breaking anywhere to prevent overflow
    $wordBreakStyle = 'break-all';
    $lineBreakStyle = 'anywhere';
}

// Build HTML for PDF
$html = '<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>' . htmlspecialchars(__('pdf.title')) . '</title>
    <style>
        * {
            font-family: ' . $cssFontFamily . ';
            word-wrap: break-word;
            overflow-wrap: ' . $lineBreakStyle . ';
        }
        body {
            font-family: ' . $cssFontFamily . ';
            color: #333;
            line-height: 1.6;
            margin: 0;
            padding: 15px;
            font-size: 11pt;
            word-break: ' . $wordBreakStyle . ';
            max-width: 100%;
            box-sizing: border-box;
        }
        h1, h2, h3, h4, h5, h6, p, li, td, th, span, div, strong, em {
            font-family: ' . $cssFontFamily . ';
            word-wrap: break-word;
            overflow-wrap: ' . $lineBreakStyle . ';
        }
        p {
            margin: 8px 0;
            line-height: 1.6;
            word-break: ' . $wordBreakStyle . ';
            white-space: normal;
            overflow-wrap: ' . $lineBreakStyle . ';
        }
        li, td, th, div {
            word-break: ' . $wordBreakStyle . ';
            white-space: normal;
            overflow-wrap: ' . $lineBreakStyle . ';
        }
        strong {
            font-weight: bold;
            display: inline;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px solid ' . $maturityColor . ';
            padding-bottom: 20px;
        }
        .header h1 {
            font-family: ' . $cssFontFamily . ';
            color: #151515;
            margin: 0 0 10px 0;
            font-size: 22px;
            line-height: 1.3;
            word-wrap: break-word;
        }
        .header .date {
            font-family: ' . $cssFontFamily . ';
            color: #666;
            font-size: 10px;
            line-height: 1.4;
        }
        .score-card {
            font-family: ' . $cssFontFamily . ';
            background: ' . $maturityColor . ';
            color: white;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
            margin-bottom: 30px;
            overflow: hidden;
            max-width: 100%;
            box-sizing: border-box;
        }
        .score-card h2 {
            font-family: ' . $cssFontFamily . ';
            margin: 0 0 12px 0;
            font-size: 20px;
            line-height: 1.3;
            word-wrap: break-word;
            overflow-wrap: ' . $lineBreakStyle . ';
            word-break: ' . $wordBreakStyle . ';
        }
        .score-circle {
            font-size: 38px;
            font-weight: bold;
            margin: 12px 0;
        }
        .score-detail {
            font-size: 11px;
            opacity: 0.9;
            word-wrap: break-word;
            overflow-wrap: ' . $lineBreakStyle . ';
            word-break: ' . $wordBreakStyle . ';
            margin: 4px 0;
        }
        .recommendation {
            margin: 12px 10px 8px 10px;
            font-size: 10px;
            line-height: 1.5;
            word-wrap: break-word;
            overflow-wrap: ' . $lineBreakStyle . ';
            word-break: ' . $wordBreakStyle . ';
            max-width: 100%;
            box-sizing: border-box;
        }
        .section {
            margin-bottom: 25px;
            page-break-inside: avoid;
        }
        .section h3 {
            font-family: ' . $cssFontFamily . ';
            color: ' . $maturityColor . ';
            border-bottom: 2px solid ' . $maturityColor . ';
            padding-bottom: 5px;
            margin-bottom: 15px;
            font-size: 15px;
            line-height: 1.3;
            word-wrap: break-word;
            overflow-wrap: break-word;
        }
        table {
            font-family: ' . $cssFontFamily . ';
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
            table-layout: auto;
        }
        table th {
            font-family: ' . $cssFontFamily . ';
            background: #f5f5f5;
            padding: 10px 8px;
            text-align: left;
            border: 1px solid #ddd;
            font-weight: bold;
            font-size: 9pt;
            vertical-align: top;
            line-height: 1.5;
            word-wrap: break-word;
        }
        table td {
            font-family: ' . $cssFontFamily . ';
            padding: 10px 8px;
            border: 1px solid #ddd;
            font-size: 9pt;
            vertical-align: top;
            line-height: 1.5;
            word-wrap: break-word;
        }
        .badge {
            font-family: ' . $cssFontFamily . ';
            display: inline-block;
            padding: 4px 8px;
            border-radius: 4px;
            color: white;
            font-weight: bold;
            font-size: 10px;
        }
        .badge-initial { background: #c9190b; }
        .badge-managed { background: #ec7a08; }
        .badge-defined { background: #ffc107; color: #000; }
        .badge-quantitative { background: #8bc34a; color: #000; }
        .badge-optimizing { background: #2aaa04; }
        .unknown-list {
            margin: 15px 0;
        }
        .unknown-item {
            font-family: ' . $cssFontFamily . ';
            background: #f9f9f9;
            padding: 10px;
            margin: 10px 0;
            border-left: 4px solid #0066cc;
            overflow: hidden;
            word-wrap: break-word;
        }
        .unknown-item strong {
            font-family: ' . $cssFontFamily . ';
            display: block;
            margin-bottom: 5px;
            color: #0066cc;
            font-size: 10pt;
            line-height: 1.4;
            word-wrap: break-word;
        }
        .improvement-section {
            font-family: ' . $cssFontFamily . ';
            background: #f9f9f9;
            padding: 12px;
            border-left: 4px solid ' . $maturityColor . ';
            margin: 20px 0;
            page-break-inside: avoid;
            overflow: hidden;
            max-width: 100%;
            box-sizing: border-box;
        }
        .improvement-section h4 {
            font-family: ' . $cssFontFamily . ';
            margin-top: 0;
            margin-bottom: 10px;
            color: ' . $maturityColor . ';
            font-size: 13px;
            line-height: 1.4;
            word-wrap: break-word;
            overflow-wrap: ' . $lineBreakStyle . ';
            word-break: ' . $wordBreakStyle . ';
        }
        .improvement-section p {
            margin: 8px 0;
            font-size: 10pt;
            line-height: 1.6;
            word-wrap: break-word;
            overflow-wrap: ' . $lineBreakStyle . ';
            word-break: ' . $wordBreakStyle . ';
        }
        .improvement-section ul {
            font-family: ' . $cssFontFamily . ';
            margin: 10px 0;
            padding-left: 18px;
            padding-right: 8px;
            line-height: 1.6;
            max-width: 100%;
            box-sizing: border-box;
        }
        .improvement-section li {
            font-family: ' . $cssFontFamily . ';
            margin: 8px 0;
            font-size: 9pt;
            line-height: 1.5;
            word-wrap: break-word;
            overflow-wrap: ' . $lineBreakStyle . ';
            word-break: ' . $wordBreakStyle . ';
        }
        ul, ol {
            line-height: 1.5;
            margin: 10px 0;
            padding-left: 18px;
            padding-right: 5px;
            max-width: 100%;
        }
        li {
            margin: 6px 0;
            line-height: 1.5;
            word-wrap: break-word;
            overflow-wrap: break-word;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 15px;
            border-top: 1px solid #ddd;
            font-size: 8pt;
            color: #666;
            line-height: 1.4;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>' . htmlspecialchars(__('pdf.title')) . '</h1>
        <div class="date">' . htmlspecialchars(__('results.assessment_date')) . ': ' . htmlspecialchars($assessmentDate) . '</div>
    </div>

    <div class="score-card">
        <h2>' . htmlspecialchars($maturityLevel) . ' ' . htmlspecialchars(__('results.maturity_level')) . '</h2>
        <div class="score-circle">' . $scorePercentage . '%</div>
        <div class="score-detail">' . number_format($weightedScore, 1) . ' ' . htmlspecialchars(__('results.of_points', ['max' => $maxScore])) . ' (weighted)</div>
        <div class="score-detail" style="font-size: 0.8em; color: #666;">' . htmlspecialchars(__('results.raw_score', ['score' => $totalScore])) . ' | ' . htmlspecialchars(__('results.profile')) . ': ' . htmlspecialchars(__($profileData['name_key'])) . '</div>
        <div class="recommendation">' . htmlspecialchars($recommendationDetail) . '</div>
    </div>

    <div class="section" style="background: #f9f9f9; padding: 15px; border-radius: 4px; margin-bottom: 20px;">
        <h3 style="margin-top: 0; color: #333; border-bottom: none; font-size: 14px;">Profile Information</h3>
        <p style="margin: 5px 0; font-size: 11pt;"><strong>' . htmlspecialchars(__($profileData['name_key'])) . '</strong></p>
        <p style="margin: 5px 0; color: #666; font-size: 10pt;">' . htmlspecialchars(__($profileData['description_key'])) . '</p>
    </div>

    <div class="section">
        <h3>' . htmlspecialchars(__('results.domain_analysis')) . '</h3>
        <p style="font-size: 10pt; color: #666; margin: 10px 0;">' . htmlspecialchars(__('results.domain_analysis.weights_note', ['profile' => __($profileData['name_key'])])) . '</p>
        <table>
            <thead>
                <tr>
                    <th>' . htmlspecialchars(__('results.table.domain')) . '</th>
                    <th style="text-align: center;">' . htmlspecialchars(__('results.table.score')) . '</th>
                    <th style="text-align: center;">' . htmlspecialchars(__('results.table.weight')) . '</th>
                    <th style="text-align: center;">' . htmlspecialchars(__('results.table.progress')) . '</th>
                    <th>' . htmlspecialchars(__('results.table.maturity')) . '</th>
                </tr>
            </thead>
            <tbody>';

foreach ($questions as $domainName => $domainData) {
    $score = $domainScores[$domainName] ?? 0;
    $maxDomainScore = count($domainData['questions']);
    $percentage = $maxDomainScore > 0 ? round(($score / $maxDomainScore) * 100) : 0;
    $weight = $domainWeights[$domainName] ?? 1.0;

    if ($percentage == 0) {
        $badge = 'initial';
        $levelTextKey = 'maturity.initial';
    } elseif ($percentage <= 20) {
        $badge = 'initial';
        $levelTextKey = 'maturity.initial';
    } elseif ($percentage <= 40) {
        $badge = 'managed';
        $levelTextKey = 'maturity.managed';
    } elseif ($percentage <= 60) {
        $badge = 'defined';
        $levelTextKey = 'maturity.defined';
    } elseif ($percentage <= 80) {
        $badge = 'quantitative';
        $levelTextKey = 'maturity.quantitative';
    } else {
        $badge = 'optimizing';
        $levelTextKey = 'maturity.optimizing';
    }

    $weightStyle = $weight >= 1.5 ? 'background: #f0ab00; color: #fff; font-weight: bold;' : 'background: #f5f5f5; color: #333;';

    $html .= '<tr>
                <td><strong>' . htmlspecialchars(__($domainData['name_key'])) . '</strong></td>
                <td style="text-align: center;">' . $score . '/' . $maxDomainScore . '</td>
                <td style="text-align: center;"><span style="display: inline-block; padding: 3px 8px; border-radius: 3px; ' . $weightStyle . '">' . number_format($weight, 1) . '&times;</span></td>
                <td style="text-align: center;">' . $percentage . '%</td>
                <td><span class="badge badge-' . $badge . '">' . htmlspecialchars(__($levelTextKey)) . '</span></td>
              </tr>';
}

$html .= '  </tbody>
        </table>
    </div>';

// Recommended Improvement Actions section
$html .= '<div class="section">
    <h3>' . htmlspecialchars(__('results.improvement_actions')) . '</h3>';

if ($maturityLevelKey === 'maturity.initial') {
    $html .= '<div class="improvement-section">
        <h4>' . __('improvement.initial.title') . '</h4>
        <p>' . __('improvement.initial.intro') . '</p>
        <ul>
            <li>' . __('improvement.initial.action1') . '</li>
            <li>' . __('improvement.initial.action2') . '</li>
            <li>' . __('improvement.initial.action3') . '</li>
            <li>' . __('improvement.initial.action4') . '</li>
            <li>' . __('improvement.initial.action5') . '</li>
            <li>' . __('improvement.initial.action6') . '</li>
        </ul>
        <h4>' . __('improvement.initial.priorities') . '</h4>
        <ul>
            <li>' . __('improvement.initial.priority1') . '</li>
            <li>' . __('improvement.initial.priority2') . '</li>
            <li>' . __('improvement.initial.priority3') . '</li>
            <li>' . __('improvement.initial.priority4') . '</li>
        </ul>
    </div>';
} elseif ($maturityLevelKey === 'maturity.managed') {
    $html .= '<div class="improvement-section">
        <h4>' . __('improvement.managed.title') . '</h4>
        <p>' . __('improvement.managed.intro') . '</p>
        <ul>
            <li>' . __('improvement.managed.action1') . '</li>
            <li>' . __('improvement.managed.action2') . '</li>
            <li>' . __('improvement.managed.action3') . '</li>
            <li>' . __('improvement.managed.action4') . '</li>
            <li>' . __('improvement.managed.action5') . '</li>
            <li>' . __('improvement.managed.action6') . '</li>
        </ul>
        <h4>' . __('improvement.managed.focus') . '</h4>
        <ul>
            <li>' . __('improvement.managed.focus1') . '</li>
            <li>' . __('improvement.managed.focus2') . '</li>
            <li>' . __('improvement.managed.focus3') . '</li>
            <li>' . __('improvement.managed.focus4') . '</li>
        </ul>
    </div>';
} elseif ($maturityLevelKey === 'maturity.defined') {
    $html .= '<div class="improvement-section">
        <h4>' . __('improvement.defined.title') . '</h4>
        <p>' . __('improvement.defined.intro') . '</p>
        <ul>
            <li>' . __('improvement.defined.action1') . '</li>
            <li>' . __('improvement.defined.action2') . '</li>
            <li>' . __('improvement.defined.action3') . '</li>
            <li>' . __('improvement.defined.action4') . '</li>
            <li>' . __('improvement.defined.action5') . '</li>
            <li>' . __('improvement.defined.action6') . '</li>
        </ul>
        <h4>' . __('improvement.defined.priorities') . '</h4>
        <ul>
            <li>' . __('improvement.defined.priority1') . '</li>
            <li>' . __('improvement.defined.priority2') . '</li>
            <li>' . __('improvement.defined.priority3') . '</li>
            <li>' . __('improvement.defined.priority4') . '</li>
        </ul>
    </div>';
} elseif ($maturityLevelKey === 'maturity.quantitative') {
    $html .= '<div class="improvement-section">
        <h4>' . __('improvement.quantitative.title') . '</h4>
        <p>' . __('improvement.quantitative.intro') . '</p>
        <ul>
            <li>' . __('improvement.quantitative.action1') . '</li>
            <li>' . __('improvement.quantitative.action2') . '</li>
            <li>' . __('improvement.quantitative.action3') . '</li>
            <li>' . __('improvement.quantitative.action4') . '</li>
            <li>' . __('improvement.quantitative.action5') . '</li>
            <li>' . __('improvement.quantitative.action6') . '</li>
        </ul>
        <h4>' . __('improvement.quantitative.focus') . '</h4>
        <ul>
            <li>' . __('improvement.quantitative.focus1') . '</li>
            <li>' . __('improvement.quantitative.focus2') . '</li>
            <li>' . __('improvement.quantitative.focus3') . '</li>
            <li>' . __('improvement.quantitative.focus4') . '</li>
        </ul>
    </div>';
} else {
    $html .= '<div class="improvement-section">
        <h4>' . __('improvement.optimizing.title') . '</h4>
        <p>' . __('improvement.optimizing.intro') . '</p>
        <ul>
            <li>' . __('improvement.optimizing.action1') . '</li>
            <li>' . __('improvement.optimizing.action2') . '</li>
            <li>' . __('improvement.optimizing.action3') . '</li>
            <li>' . __('improvement.optimizing.action4') . '</li>
            <li>' . __('improvement.optimizing.action5') . '</li>
            <li>' . __('improvement.optimizing.action6') . '</li>
        </ul>
        <p>' . __('improvement.optimizing.note') . '</p>
    </div>';
}

$html .= '</div>';

// Detailed Domain Insights section
$html .= '<div class="section">
    <h3>' . htmlspecialchars(__('results.domain_insights')) . '</h3>
    <p style="font-size: 10pt; margin-bottom: 15px;">' . htmlspecialchars(__('results.domain_insights.intro')) . '</p>';

$hasAnyRequirements = false;
foreach ($questions as $domainName => $domainData) {
    $score = $domainScores[$domainName] ?? 0;
    $responses = $domainResponses[$domainName] ?? [];

    if ($score > 0) {
        $hasAnyRequirements = true;
        $html .= '<div style="background: #f9f9f9; padding: 12px; margin: 12px 0; border-left: 4px solid ' . $maturityColor . '; page-break-inside: avoid;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                <h4 style="margin: 0; color: #333; font-size: 13pt;">' . htmlspecialchars(__($domainData['name_key'])) . '</h4>
                <span style="background: ' . $maturityColor . '; color: white; padding: 4px 10px; border-radius: 4px; font-weight: bold; font-size: 11pt;">' . $score . '/' . count($domainData['questions']) . '</span>
            </div>
            <p style="margin: 8px 0; color: #666; font-size: 10pt;">' . htmlspecialchars(__($domainData['description_key'])) . '</p>
            <div style="margin-top: 10px;">
                <strong style="font-size: 10pt; color: #333;">' . htmlspecialchars(__('results.domain_insights.requirements_identified')) . '</strong>
                <ul style="margin: 5px 0; padding-left: 20px;">';

        foreach ($responses as $response_key) {
            $html .= '<li style="margin: 4px 0; font-size: 10pt; color: #333;">' . htmlspecialchars(__($response_key)) . '</li>';
        }

        $html .= '</ul>
            </div>
        </div>';
    }
}

if (!$hasAnyRequirements) {
    $html .= '<p style="padding: 15px; background: #f9f9f9; border-left: 4px solid #0066cc; margin: 10px 0; font-size: 10pt;">' . htmlspecialchars(__('results.domain_insights.no_requirements')) . '</p>';
}

$html .= '</div>';

// Questions to Research section
if (!empty($unknownQuestions)) {
    $html .= '<div class="section">
        <h3>' . htmlspecialchars(__('results.research_questions')) . '</h3>
        <p>' . htmlspecialchars(__('results.research_questions.description')) . '</p>
        <div class="unknown-list">';

    $unknownByDomain = [];
    foreach ($unknownQuestions as $uq) {
        $unknownByDomain[$uq['domain']][] = $uq;
    }

    foreach ($unknownByDomain as $domainName => $domainUnknowns) {
        $html .= '<h4 style="color: #0066cc; margin-top: 15px;">' . htmlspecialchars(__($domainUnknowns[0]['domain_key'])) . '</h4>';
        foreach ($domainUnknowns as $uq) {
            $html .= '<div class="unknown-item">
                        <strong>' . htmlspecialchars(__($uq['question_key'])) . '</strong>';
            if (!empty($uq['tooltip_key'])) {
                $html .= '<p style="margin: 5px 0 0 0; font-size: 10pt; color: #666;">' . htmlspecialchars(__($uq['tooltip_key'])) . '</p>';
            }
            $html .= '</div>';
        }
    }

    $html .= '</div></div>';
}

$html .= '
    <div class="footer">
        <p>Generated by Viewfinder Lite - Digital Sovereignty Readiness Assessment</p>
        <p>' . htmlspecialchars($assessmentDate) . '</p>
    </div>
</body>
</html>';

// Configure Dompdf
$options = new Options();
$options->set('isHtml5ParserEnabled', true);
$options->set('isRemoteEnabled', false);
$options->set('defaultFont', $fontFamily);
$options->set('chroot', realpath(__DIR__ . '/..'));
$options->set('fontDir', __DIR__ . '/../vendor/dompdf/dompdf/lib/fonts/');
$options->set('fontCache', __DIR__ . '/../vendor/dompdf/dompdf/lib/fonts/');
$options->set('isFontSubsettingEnabled', true);

// Initialize Dompdf
$dompdf = new Dompdf($options);

// Load HTML content
$dompdf->loadHtml($html);

// Set paper size
$dompdf->setPaper('A4', 'portrait');

// Render PDF
$dompdf->render();

// Output PDF for download
$filename = 'DS-Readiness-Assessment-' . date('Y-m-d-His') . '.pdf';
$dompdf->stream($filename, ['Attachment' => true]);
