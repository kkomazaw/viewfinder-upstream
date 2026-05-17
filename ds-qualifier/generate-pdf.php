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

// Build HTML for PDF
$html = '<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>' . htmlspecialchars(__('pdf.title')) . '</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            color: #333;
            line-height: 1.6;
            margin: 0;
            padding: 20px;
            font-size: 11pt;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px solid ' . $maturityColor . ';
            padding-bottom: 20px;
        }
        .header h1 {
            color: #151515;
            margin: 0 0 10px 0;
            font-size: 24px;
        }
        .header .date {
            color: #666;
            font-size: 11px;
        }
        .score-card {
            background: ' . $maturityColor . ';
            color: white;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
            margin-bottom: 30px;
        }
        .score-card h2 {
            margin: 0 0 15px 0;
            font-size: 26px;
        }
        .score-circle {
            font-size: 42px;
            font-weight: bold;
            margin: 15px 0;
        }
        .score-detail {
            font-size: 13px;
            opacity: 0.9;
        }
        .recommendation {
            margin: 15px 0;
            font-size: 13px;
            line-height: 1.8;
        }
        .section {
            margin-bottom: 25px;
            page-break-inside: avoid;
        }
        .section h3 {
            color: ' . $maturityColor . ';
            border-bottom: 2px solid ' . $maturityColor . ';
            padding-bottom: 5px;
            margin-bottom: 15px;
            font-size: 16px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
        }
        table th {
            background: #f5f5f5;
            padding: 8px;
            text-align: left;
            border: 1px solid #ddd;
            font-weight: bold;
            font-size: 10pt;
        }
        table td {
            padding: 8px;
            border: 1px solid #ddd;
            font-size: 10pt;
        }
        .badge {
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
            background: #f9f9f9;
            padding: 10px;
            margin: 10px 0;
            border-left: 4px solid #0066cc;
        }
        .unknown-item strong {
            display: block;
            margin-bottom: 5px;
            color: #0066cc;
            font-size: 11pt;
        }
        .improvement-section {
            background: #f9f9f9;
            padding: 15px;
            border-left: 4px solid ' . $maturityColor . ';
            margin: 20px 0;
            page-break-inside: avoid;
        }
        .improvement-section h4 {
            margin-top: 0;
            color: ' . $maturityColor . ';
            font-size: 14px;
        }
        .improvement-section ul {
            margin: 10px 0;
            padding-left: 20px;
        }
        .improvement-section li {
            margin: 8px 0;
            font-size: 10pt;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 15px;
            border-top: 1px solid #ddd;
            font-size: 9pt;
            color: #666;
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
                <td style="text-align: center;"><span style="display: inline-block; padding: 3px 8px; border-radius: 3px; ' . $weightStyle . '">' . number_format($weight, 1) . '\u00d7</span></td>
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
        <h4>Critical Actions for Initial Level</h4>
        <p>Processes are unpredictable and reactive. Establish basic digital sovereignty awareness and controls:</p>
        <ul>
            <li><strong>Gain Executive Awareness:</strong> Educate leadership on digital sovereignty risks and regulatory requirements</li>
            <li><strong>Assess Current State:</strong> Conduct inventory of data locations, vendor dependencies, and compliance gaps</li>
            <li><strong>Identify Quick Wins:</strong> Address immediate sovereignty risks (e.g., data residency violations, unencrypted data)</li>
            <li><strong>Secure Resources:</strong> Obtain initial budget and staffing for sovereignty initiatives</li>
            <li><strong>Define Initial Policies:</strong> Create basic policies for data handling and vendor selection</li>
            <li><strong>Build Awareness:</strong> Launch awareness campaigns to educate staff about digital sovereignty</li>
        </ul>
        <h4>Immediate Priorities:</h4>
        <ul>
            <li>Executive sponsorship and steering committee formation</li>
            <li>Critical data classification and residency mapping</li>
            <li>Vendor dependency assessment</li>
            <li>Compliance requirement documentation (GDPR, NIS2, etc.)</li>
        </ul>
    </div>';
} elseif ($maturityLevelKey === 'maturity.managed') {
    $html .= '<div class="improvement-section">
        <h4>Foundation Actions for Managed Level</h4>
        <p>Projects are managed but processes are not yet standardized. Build repeatable practices:</p>
        <ul>
            <li><strong>Develop Strategy:</strong> Create a digital sovereignty roadmap aligned with business objectives</li>
            <li><strong>Implement Controls:</strong> Deploy encryption key management (BYOK/HYOK) and data residency controls</li>
            <li><strong>Establish Governance:</strong> Form sovereignty governance committee with clear responsibilities</li>
            <li><strong>Document Procedures:</strong> Create standard operating procedures for sovereignty-critical activities</li>
            <li><strong>Build Capabilities:</strong> Train technical teams on sovereign technologies and frameworks</li>
            <li><strong>Evaluate Solutions:</strong> Research open-source and sovereign-ready platforms</li>
        </ul>
        <h4>Key Focus Areas:</h4>
        <ul>
            <li>Data sovereignty and encryption controls</li>
            <li>Repeatable assessment processes</li>
            <li>Vendor risk management framework</li>
            <li>Compliance tracking and reporting</li>
        </ul>
    </div>';
} elseif ($maturityLevelKey === 'maturity.defined') {
    $html .= '<div class="improvement-section">
        <h4>Standardization Actions for Defined Level</h4>
        <p>Processes are documented and standardized. Focus on organization-wide consistency and optimization:</p>
        <ul>
            <li><strong>Standardize Processes:</strong> Ensure sovereignty practices are consistent across all business units</li>
            <li><strong>Implement Standards:</strong> Adopt open standards and containerization for portability</li>
            <li><strong>Enhance Controls:</strong> Implement advanced monitoring, audit rights, and security log sovereignty</li>
            <li><strong>Build Resilience:</strong> Develop and test disaster recovery plans for geopolitical scenarios</li>
            <li><strong>Expand Open Source:</strong> Increase use of open-source software and contribute to strategic projects</li>
            <li><strong>Pursue Certifications:</strong> Obtain relevant certifications (NIS2, SecNumCloud, FedRAMP, etc.)</li>
        </ul>
        <h4>Advancement Priorities:</h4>
        <ul>
            <li>Process standardization and documentation</li>
            <li>Cloud platform portability testing</li>
            <li>Organization-wide training programs</li>
            <li>Sovereignty metrics and KPIs definition</li>
        </ul>
    </div>';
} elseif ($maturityLevelKey === 'maturity.quantitative') {
    $html .= '<div class="improvement-section">
        <h4>Measurement Actions for Quantitatively Managed Level</h4>
        <p>Processes are measured and statistically controlled. Optimize through data-driven decisions:</p>
        <ul>
            <li><strong>Establish Metrics:</strong> Define and track quantitative sovereignty performance indicators</li>
            <li><strong>Analyze Performance:</strong> Use statistical techniques to understand process variations</li>
            <li><strong>Set Objectives:</strong> Establish quantitative quality and performance targets for sovereignty</li>
            <li><strong>Validate Controls:</strong> Regularly test and measure effectiveness of sovereignty controls</li>
            <li><strong>Benchmark Performance:</strong> Compare your metrics against industry standards and peers</li>
            <li><strong>Optimize Resources:</strong> Use data to identify and eliminate inefficiencies</li>
        </ul>
        <h4>Excellence Focus:</h4>
        <ul>
            <li>Advanced analytics and metrics dashboards</li>
            <li>Statistical process control techniques</li>
            <li>Continuous monitoring and alerting</li>
            <li>Performance baselines and targets</li>
        </ul>
    </div>';
} else {
    $html .= '<div class="improvement-section">
        <h4>Innovation Actions for Optimizing Level</h4>
        <p>Focus on continuous improvement and innovation. Lead industry best practices:</p>
        <ul>
            <li><strong>Drive Innovation:</strong> Pilot and deploy innovative sovereignty technologies and practices</li>
            <li><strong>Continuous Improvement:</strong> Use quantitative feedback to continuously optimize processes</li>
            <li><strong>Share Knowledge:</strong> Document and share best practices with industry and open-source communities</li>
            <li><strong>Lead Standards:</strong> Contribute to and influence digital sovereignty standards and frameworks</li>
            <li><strong>Expand Scope:</strong> Apply sovereignty principles to emerging technologies (AI, edge, quantum)</li>
            <li><strong>Stay Ahead:</strong> Proactively monitor and adapt to evolving regulations and geopolitical changes</li>
        </ul>
        <p><strong>Note:</strong> At the Optimizing level, your focus shifts from implementing controls to driving innovation and thought leadership in digital sovereignty.</p>
    </div>';
}

$html .= '</div>';

// Detailed Domain Insights section
$html .= '<div class="section">
    <h3>' . htmlspecialchars(__('results.domain_insights')) . '</h3>
    <p style="font-size: 10pt; margin-bottom: 15px;">Review your specific responses across all domains:</p>';

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
                <strong style="font-size: 10pt; color: #333;">Requirements Identified:</strong>
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
    $html .= '<p style="padding: 15px; background: #f9f9f9; border-left: 4px solid #0066cc; margin: 10px 0; font-size: 10pt;">
                <strong>No Digital Sovereignty requirements were identified in this assessment.</strong> Consider focusing on other value propositions.
              </p>';
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
$options->set('defaultFont', 'DejaVu Sans'); // DejaVu Sans supports Unicode including Japanese characters

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
