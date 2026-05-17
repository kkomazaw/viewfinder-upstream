<?php
// Start session for locale management and assessment data
session_start();

// Load internationalization
require_once __DIR__ . '/../i18n/I18n.php';

// Handle locale change request
if (isset($_GET['locale'])) {
    setAppLocale($_GET['locale']);
    // Redirect to remove locale parameter from URL
    header('Location: ' . strtok($_SERVER['REQUEST_URI'], '?'));
    exit;
}
?>
<!doctype html>
<html lang="<?php echo getLocale(); ?>" dir="<?php echo getTextDirection(); ?>" class="pf-theme-dark">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?php echo __e('results.title'); ?> - Viewfinder</title>

  <!-- Reuse existing CSS from parent directory -->
  <link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
  <link rel="stylesheet" href="../css/bootstrap.min.css">
  <link rel="stylesheet" href="../css/brands.css" />
  <link rel="stylesheet" href="../css/style.css" />
  <link rel="stylesheet" href="../css/tab-dark.css" />
  <link rel="stylesheet" href="../css/patternfly.css" />
  <link rel="stylesheet" href="../css/patternfly-addons.css" />

  <!-- DS Qualifier specific styles -->
  <link rel="stylesheet" href="css/ds-qualifier.css" />

  <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
  <script src="https://kit.fontawesome.com/8a8c57f9cf.js" crossorigin="anonymous"></script>

  <style>
    body {
      background-color: #151515 !important;
      color: #ccc !important;
    }
    .pf-c-page__header-tools button {
      margin-right: 1rem;
    }
    .widget {
      padding-top: 1rem;
    }
    @media print {
      .no-print { display: none; }
      .score-card { page-break-after: avoid; }
    }
  </style>
</head>

<body>
  <!-- Language Selector -->
  <div class="language-selector no-print" style="position: fixed; top: 20px; right: 20px; z-index: 1000;">
    <label for="language-select" style="color: #9ec7fc; margin-right: 0.5rem;">
      <i class="fa-solid fa-globe"></i>
    </label>
    <select id="language-select"
            onchange="changeLanguage(this.value)"
            style="background: #2a2a2a; color: #ccc; border: 1px solid #444; padding: 0.5rem; border-radius: 4px; cursor: pointer;">
      <?php
      $availableLocales = getAvailableLocales();
      $currentLocale = getLocale();

      foreach ($availableLocales as $locale):
      ?>
        <option value="<?php echo $locale; ?>"
                <?php echo $locale === $currentLocale ? 'selected' : ''; ?>>
          <?php echo getLocaleName($locale); ?>
        </option>
      <?php endforeach; ?>
    </select>
  </div>

  <header class="pf-c-page__header no-print">
    <div class="pf-c-page__header-brand">
      <div class="pf-c-page__header-brand-toggle"></div>
    </div>

    <div class="widget">
      <a href="../index.php"><button><i class="fa-solid fa-home"></i> <?php echo __e('common.home'); ?></button></a>
      <a href="index.php"><button style="margin-left: 1rem;"><?php echo __e('assessment.button.new'); ?></button></a>
    </div>
  </header>

  <div class="container">
    <?php
    // Store POST data in session for PDF generator
    $_SESSION['assessment_data'] = $_POST;

    // Load questions configuration for domain mapping
    $questions = require_once 'config.php';

    // Load profiles and get selected profile
    $profiles = require_once 'profiles.php';
    $selectedProfile = isset($_POST['profile']) ? $_POST['profile'] : 'balanced';

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
            if (isset($_POST[$paramName])) {
                $weight = floatval($_POST[$paramName]);
                // Validate weight is between 1.0 and 2.0
                $domainWeights[$domainName] = max(1.0, min(2.0, $weight));
            } else {
                $domainWeights[$domainName] = 1.0;
            }
        }
        // Profile description key already set in profiles.php
    } else {
        $domainWeights = $profileData['weights'];
    }

    // Initialize scoring arrays
    $totalScore = 0;
    $weightedScore = 0;
    $maxScore = 21;
    $domainScores = [];
    $domainMaxScores = [];
    $domainWeightedScores = [];
    $domainResponses = [];
    $unknownQuestions = []; // Track "Don't Know" responses

    // Map domain keys to display names
    $domainKeyMap = [];
    foreach ($questions as $domainName => $domainData) {
        $domainKeyMap[$domainData['domain_key']] = $domainName;
        $domainScores[$domainName] = 0;
        $domainMaxScores[$domainName] = count($domainData['questions']);
        $domainResponses[$domainName] = [];
    }

    // Calculate scores (both raw and weighted)
    foreach ($_POST as $key => $value) {
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
                            // Don't count toward score, but don't penalize either
                        } else {
                            $intValue = intval($value);
                            $totalScore += $intValue;
                            $domainScores[$domainName] += $intValue;
                            // Only add to responses if answer was "Yes" (value > 0)
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
    // Initial: 0-20% (0-4.2 points), Managed: 21-40% (4.21-8.4 points)
    // Defined: 41-60% (8.41-12.6 points), Quantitatively Managed: 61-80% (12.61-16.8 points)
    // Optimizing: 81-100% (16.81-21 points)
    if ($weightedScore <= 4.2) {
        $maturityLevel = 'maturity.initial';
        $priorityClass = 'maturity-initial';
        $priorityIcon = 'fa-circle-exclamation';
        $recommendationDetail = 'maturity.initial.description';
    } elseif ($weightedScore <= 8.4) {
        $maturityLevel = 'maturity.managed';
        $priorityClass = 'maturity-managed';
        $priorityIcon = 'fa-clipboard-list';
        $recommendationDetail = 'maturity.managed.description';
    } elseif ($weightedScore <= 12.6) {
        $maturityLevel = 'maturity.defined';
        $priorityClass = 'maturity-defined';
        $priorityIcon = 'fa-sitemap';
        $recommendationDetail = 'maturity.defined.description';
    } elseif ($weightedScore <= 16.8) {
        $maturityLevel = 'maturity.quantitative';
        $priorityClass = 'maturity-quantitative';
        $priorityIcon = 'fa-chart-line';
        $recommendationDetail = 'maturity.quantitative.description';
    } else {
        $maturityLevel = 'maturity.optimizing';
        $priorityClass = 'maturity-optimizing';
        $priorityIcon = 'fa-rocket';
        $recommendationDetail = 'maturity.optimizing.description';
    }

    $assessmentDate = date('F j, Y \a\t g:i A');
    ?>

    <!-- Results Header -->
    <div class="results-header">
      <h1><i class="fa-solid fa-chart-bar"></i> <?php echo __e('results.title'); ?></h1>
      <p class="assessment-date"><strong><?php echo __e('results.assessment_date'); ?></strong> <?php echo $assessmentDate; ?></p>

      <!-- Profile Information -->
      <div style="text-align: center; margin-top: 1rem; padding: 1rem; background: #1a1a1a; border-radius: 4px; border: 1px solid #444;">
        <i class="fa-solid <?php echo htmlspecialchars($profileData['icon']); ?>" style="color: #0d60f8; margin-right: 0.5rem; font-size: 1.2rem;"></i>
        <strong style="color: #9ec7fc; font-size: 1.1rem;"><?php echo __e('results.profile'); ?></strong>
        <span style="color: #fff; font-size: 1.1rem; margin-left: 0.5rem;"><?php echo __e($profileData['name_key']); ?></span>
        <p style="color: #999; margin: 0.5rem 0 0 0; font-size: 0.9rem;">
          <?php echo __e($profileData['description_key']); ?>
        </p>
      </div>
    </div>

    <!-- Score Card -->
    <div class="score-card <?php echo $priorityClass; ?>">
      <div class="score-icon">
        <i class="fa-solid <?php echo $priorityIcon; ?>"></i>
      </div>
      <h2><?php echo __e($maturityLevel); ?> <?php echo __e('results.maturity_level'); ?></h2>

      <?php
      // Calculate percentage for visual display (based on weighted score)
      $scorePercentage = round(($weightedScore / $maxScore) * 100);
      ?>

      <div class="score-visual-container">
        <div class="circular-progress" data-percentage="<?php echo $scorePercentage; ?>">
          <svg class="progress-ring" width="200" height="200">
            <circle class="progress-ring-circle-bg" cx="100" cy="100" r="90" />
            <circle class="progress-ring-circle"
                    cx="100"
                    cy="100"
                    r="90"
                    style="stroke-dasharray: <?php echo 2 * 3.14159 * 90; ?>; stroke-dashoffset: <?php echo 2 * 3.14159 * 90 * (1 - $scorePercentage / 100); ?>;" />
          </svg>
          <div class="progress-text">
            <div class="percentage-display"><?php echo $scorePercentage; ?>%</div>
            <div class="score-detail">
              <strong><?php echo number_format($weightedScore, 1); ?></strong> <?php echo __('results.of_points', ['max' => $maxScore]); ?>
              <br>
              <span style="font-size: 0.8rem; color: #999;">(<?php echo __('results.raw_score', ['score' => $totalScore]); ?>)</span>
            </div>
          </div>
        </div>
      </div>

      <h3 class="recommendation-title"><?php echo __e($maturityLevel); ?></h3>
      <p class="recommendation-detail"><?php echo __e($recommendationDetail); ?></p>
    </div>

    <!-- Domain Breakdown -->
    <div class="domain-breakdown">
      <h2><i class="fa-solid fa-table"></i> <?php echo __e('results.domain_analysis'); ?></h2>
      <p class="section-intro"><?php echo __e('results.domain_analysis.intro'); ?></p>
      <p class="section-intro" style="font-size: 0.9rem; color: #999; font-style: italic;">
        <i class="fa-solid fa-info-circle"></i> <?php echo __('results.domain_analysis.weights_note', ['profile' => __($profileData['name_key'])]); ?>
      </p>

      <div class="domain-table-wrapper">
        <table class="domain-table">
          <thead>
            <tr>
              <th><?php echo __e('results.table.domain'); ?></th>
              <th style="text-align: center;"><?php echo __e('results.table.score'); ?></th>
              <th style="text-align: center;"><?php echo __e('results.table.weight'); ?></th>
              <th style="text-align: center;"><?php echo __e('results.table.progress'); ?></th>
              <th><?php echo __e('results.table.maturity'); ?></th>
            </tr>
          </thead>
          <tbody>
            <?php
            foreach ($questions as $domainName => $domainData):
                $score = $domainScores[$domainName] ?? 0;
                $maxDomainScore = count($domainData['questions']);
                $percentage = ($score / $maxDomainScore) * 100;
                $weight = $domainWeights[$domainName] ?? 1.0;

                // Maturity levels based on score percentage (CMMI 5-level system)
                // Initial: 0-20%, Managed: 21-40%, Defined: 41-60%, Quantitatively Managed: 61-80%, Optimizing: 81-100%
                if ($percentage <= 20) {
                    $strengthClass = 'strength-initial';
                    $strengthIcon = 'fa-circle-exclamation';
                    $strengthText = 'maturity.initial';
                } elseif ($percentage <= 40) {
                    $strengthClass = 'strength-managed';
                    $strengthIcon = 'fa-clipboard-list';
                    $strengthText = 'maturity.managed';
                } elseif ($percentage <= 60) {
                    $strengthClass = 'strength-defined';
                    $strengthIcon = 'fa-sitemap';
                    $strengthText = 'maturity.defined';
                } elseif ($percentage <= 80) {
                    $strengthClass = 'strength-quantitative';
                    $strengthIcon = 'fa-chart-line';
                    $strengthText = 'maturity.quantitative';
                } else {
                    $strengthClass = 'strength-optimizing';
                    $strengthIcon = 'fa-rocket';
                    $strengthText = 'maturity.optimizing';
                }
            ?>
              <tr>
                <td><strong><?php echo __e($domainData['name_key']); ?></strong></td>
                <td style="text-align: center;">
                  <span class="domain-score-cell"><?php echo $score; ?>/<?php echo $maxDomainScore; ?></span>
                </td>
                <td style="text-align: center;">
                  <span class="weight-badge" style="display: inline-block; padding: 0.25rem 0.75rem; background: <?php echo $weight >= 1.5 ? 'linear-gradient(135deg, #f0ab00 0%, #c58c00 100%)' : '#1a1a1a'; ?>; border: 1px solid #444; border-radius: 4px; font-weight: 600; color: <?php echo $weight >= 1.5 ? '#fff' : '#9ec7fc'; ?>;">
                    <?php echo number_format($weight, 1); ?>×
                  </span>
                </td>
                <td style="text-align: center;">
                  <span class="progress-bar-wrapper">
                    <div class="progress-bar">
                      <div class="progress-fill <?php echo $strengthClass; ?>" style="width: <?php echo $percentage; ?>%;"></div>
                    </div>
                  </span>
                </td>
                <td>
                  <span class="strength-badge <?php echo $strengthClass; ?>">
                    <i class="fa-solid <?php echo $strengthIcon; ?>"></i> <?php echo __e($strengthText); ?>
                  </span>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Questions to Research -->
    <?php if (!empty($unknownQuestions)): ?>
    <div class="unknown-questions-section">
      <h2><i class="fa-solid fa-clipboard-question"></i> <?php echo __e('results.research_questions'); ?></h2>
      <p class="section-description">
        <?php echo __e('results.research_questions.description'); ?>
      </p>

      <?php
      // Group unknown questions by domain
      $unknownByDomain = [];
      foreach ($unknownQuestions as $uq) {
        $unknownByDomain[$uq['domain']][] = $uq;
      }
      ?>

      <div class="unknown-questions-list">
        <?php foreach ($unknownByDomain as $domainName => $domainUnknowns): ?>
          <div class="unknown-domain-section">
            <h3><i class="fa-solid fa-folder-open"></i> <?php echo __e($domainUnknowns[0]['domain_key']); ?></h3>
            <ul class="unknown-question-items">
              <?php foreach ($domainUnknowns as $uq): ?>
                <li class="unknown-question-item">
                  <span class="question-icon"><i class="fa-solid fa-question-circle"></i></span>
                  <div class="question-content">
                    <div class="question-text"><?php echo __e($uq['question_key']); ?></div>
                    <?php if (!empty($uq['tooltip_key'])): ?>
                      <div class="question-context">
                        <i class="fa-solid fa-lightbulb"></i>
                        <strong>Context:</strong> <?php echo __e($uq['tooltip_key']); ?>
                      </div>
                    <?php endif; ?>
                  </div>
                </li>
              <?php endforeach; ?>
            </ul>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
    <?php else: ?>
    <div class="unknown-questions-section">
      <p style="text-align: center; padding: 2rem; color: #999;">
        <i class="fa-solid fa-circle-check"></i> <?php echo __e('results.no_research_questions'); ?>
      </p>
    </div>
    <?php endif; ?>

    <!-- Improvement Actions -->
    <div class="improvement-actions">
      <h2><i class="fa-solid fa-bullseye"></i> <?php echo __e('results.improvement_actions'); ?></h2>

      <?php if ($maturityLevel === 'maturity.initial'): ?>
        <div class="action-priority maturity-initial">
          <h3><i class="fa-solid fa-circle-exclamation"></i> Critical Actions for Initial Level</h3>
          <p>Processes are unpredictable and reactive. Establish basic digital sovereignty awareness and controls:</p>
          <ul>
            <li><strong>Gain Executive Awareness:</strong> Educate leadership on digital sovereignty risks and regulatory requirements</li>
            <li><strong>Assess Current State:</strong> Conduct inventory of data locations, vendor dependencies, and compliance gaps</li>
            <li><strong>Identify Quick Wins:</strong> Address immediate sovereignty risks (e.g., data residency violations, unencrypted data)</li>
            <li><strong>Secure Resources:</strong> Obtain initial budget and staffing for sovereignty initiatives</li>
            <li><strong>Define Initial Policies:</strong> Create basic policies for data handling and vendor selection</li>
            <li><strong>Build Awareness:</strong> Launch awareness campaigns to educate staff about digital sovereignty</li>
          </ul>

          <div class="recommended-products">
            <h4>Immediate Priorities:</h4>
            <ul>
              <li>Executive sponsorship and steering committee formation</li>
              <li>Critical data classification and residency mapping</li>
              <li>Vendor dependency assessment</li>
              <li>Compliance requirement documentation (GDPR, NIS2, etc.)</li>
            </ul>
          </div>
        </div>

      <?php elseif ($maturityLevel === 'maturity.managed'): ?>
        <div class="action-priority maturity-managed">
          <h3><i class="fa-solid fa-clipboard-list"></i> Foundation Actions for Managed Level</h3>
          <p>Projects are managed but processes are not yet standardized. Build repeatable practices:</p>
          <ul>
            <li><strong>Develop Strategy:</strong> Create a digital sovereignty roadmap aligned with business objectives</li>
            <li><strong>Implement Controls:</strong> Deploy encryption key management (BYOK/HYOK) and data residency controls</li>
            <li><strong>Establish Governance:</strong> Form sovereignty governance committee with clear responsibilities</li>
            <li><strong>Document Procedures:</strong> Create standard operating procedures for sovereignty-critical activities</li>
            <li><strong>Build Capabilities:</strong> Train technical teams on sovereign technologies and frameworks</li>
            <li><strong>Evaluate Solutions:</strong> Research open-source and sovereign-ready platforms</li>
          </ul>

          <div class="recommended-products">
            <h4>Key Focus Areas:</h4>
            <ul>
              <li>Data sovereignty and encryption controls</li>
              <li>Repeatable assessment processes</li>
              <li>Vendor risk management framework</li>
              <li>Compliance tracking and reporting</li>
            </ul>
          </div>
        </div>

      <?php elseif ($maturityLevel === 'maturity.defined'): ?>
        <div class="action-priority maturity-defined">
          <h3><i class="fa-solid fa-sitemap"></i> Standardization Actions for Defined Level</h3>
          <p>Processes are documented and standardized. Focus on organization-wide consistency and optimization:</p>
          <ul>
            <li><strong>Standardize Processes:</strong> Ensure sovereignty practices are consistent across all business units</li>
            <li><strong>Implement Standards:</strong> Adopt open standards and containerization for portability</li>
            <li><strong>Enhance Controls:</strong> Implement advanced monitoring, audit rights, and security log sovereignty</li>
            <li><strong>Build Resilience:</strong> Develop and test disaster recovery plans for geopolitical scenarios</li>
            <li><strong>Expand Open Source:</strong> Increase use of open-source software and contribute to strategic projects</li>
            <li><strong>Pursue Certifications:</strong> Obtain relevant certifications (NIS2, SecNumCloud, FedRAMP, etc.)</li>
          </ul>

          <div class="recommended-resources">
            <h4>Advancement Priorities:</h4>
            <ul>
              <li>Process standardization and documentation</li>
              <li>Cloud platform portability testing</li>
              <li>Organization-wide training programs</li>
              <li>Sovereignty metrics and KPIs definition</li>
            </ul>
          </div>
        </div>

      <?php elseif ($maturityLevel === 'maturity.quantitative'): ?>
        <div class="action-priority maturity-quantitative">
          <h3><i class="fa-solid fa-chart-line"></i> Measurement Actions for Quantitatively Managed Level</h3>
          <p>Processes are measured and statistically controlled. Optimize through data-driven decisions:</p>
          <ul>
            <li><strong>Establish Metrics:</strong> Define and track quantitative sovereignty performance indicators</li>
            <li><strong>Analyze Performance:</strong> Use statistical techniques to understand process variations</li>
            <li><strong>Set Objectives:</strong> Establish quantitative quality and performance targets for sovereignty</li>
            <li><strong>Validate Controls:</strong> Regularly test and measure effectiveness of sovereignty controls</li>
            <li><strong>Benchmark Performance:</strong> Compare your metrics against industry standards and peers</li>
            <li><strong>Optimize Resources:</strong> Use data to identify and eliminate inefficiencies</li>
          </ul>

          <div class="recommended-resources">
            <h4>Excellence Focus:</h4>
            <ul>
              <li>Advanced analytics and metrics dashboards</li>
              <li>Statistical process control techniques</li>
              <li>Continuous monitoring and alerting</li>
              <li>Performance baselines and targets</li>
            </ul>
          </div>
        </div>

      <?php else: ?>
        <div class="action-priority maturity-optimizing">
          <h3><i class="fa-solid fa-rocket"></i> Innovation Actions for Optimizing Level</h3>
          <p>Focus on continuous improvement and innovation. Lead industry best practices:</p>
          <ul>
            <li><strong>Drive Innovation:</strong> Pilot and deploy innovative sovereignty technologies and practices</li>
            <li><strong>Continuous Improvement:</strong> Use quantitative feedback to continuously optimize processes</li>
            <li><strong>Share Knowledge:</strong> Document and share best practices with industry and open-source communities</li>
            <li><strong>Lead Standards:</strong> Contribute to and influence digital sovereignty standards and frameworks</li>
            <li><strong>Expand Scope:</strong> Apply sovereignty principles to emerging technologies (AI, edge, quantum)</li>
            <li><strong>Stay Ahead:</strong> Proactively monitor and adapt to evolving regulations and geopolitical changes</li>
          </ul>

          <p class="note"><strong>Note:</strong> At the Optimizing level, your focus shifts from implementing controls to driving innovation and thought leadership in digital sovereignty. Continue to measure, refine, and lead industry practices.</p>
        </div>
      <?php endif; ?>
    </div>

    <!-- Detailed Domain Insights -->
    <div class="domain-insights">
      <h2><i class="fa-solid fa-list-check"></i> <?php echo __e('results.domain_insights'); ?></h2>
      <p class="section-intro">Review your specific responses across all domains:</p>

      <?php foreach ($questions as $domainName => $domainData):
          $score = $domainScores[$domainName] ?? 0;
          $responses = $domainResponses[$domainName] ?? [];

          if ($score > 0):
      ?>
        <div class="domain-insight-card">
          <div class="domain-insight-header">
            <h3><?php echo __e($domainData['name_key']); ?></h3>
            <span class="insight-score"><?php echo $score; ?>/<?php echo count($domainData['questions']); ?></span>
          </div>
          <p class="domain-insight-description"><?php echo __e($domainData['description_key']); ?></p>

          <div class="requirements-found">
            <h4>Requirements Identified:</h4>
            <ul>
              <?php foreach ($responses as $response_key): ?>
                <li><i class="fa-solid fa-check"></i> <?php echo __e($response_key); ?></li>
              <?php endforeach; ?>
            </ul>
          </div>
        </div>
      <?php
          endif;
        endforeach;
      ?>

      <?php if ($totalScore === 0): ?>
        <div class="no-requirements">
          <p><i class="fa-solid fa-info-circle"></i> No Digital Sovereignty requirements were identified in this assessment. Consider focusing on other Red Hat value propositions.</p>
        </div>
      <?php endif; ?>
    </div>

    <!-- Action Buttons -->
    <div class="form-actions no-print">
      <a href="generate-pdf.php" class="btn-primary">
        <i class="fa-solid fa-file-pdf"></i> <?php echo __e('results.download_pdf'); ?>
      </a>
      <a href="index.php" class="btn-secondary">
        <i class="fa-solid fa-rotate-left"></i> <?php echo __e('results.take_new'); ?>
      </a>
    </div>

    <!-- Footer -->
    <div class="results-footer">
      <p><small>Generated by Viewfinder Digital Sovereignty Readiness Assessment on <?php echo $assessmentDate; ?></small></p>
    </div>
  </div>

  <script>
    // Language change function
    function changeLanguage(locale) {
      window.location.href = '?locale=' + locale;
    }
  </script>

  <style>
    .language-selector select:hover {
      border-color: #0d60f8;
    }

    .language-selector select:focus {
      outline: none;
      border-color: #0d60f8;
      box-shadow: 0 0 0 2px rgba(13, 96, 248, 0.2);
    }
  </style>
</body>
</html>
