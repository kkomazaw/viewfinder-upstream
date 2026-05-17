<?php
// Start session for locale management
session_start();

// Load internationalization
require_once __DIR__ . '/../i18n/I18n.php';

// Handle locale change request
if (isset($_GET['locale'])) {
    setAppLocale($_GET['locale']);
    // Rebuild URL without locale parameter
    $url = strtok($_SERVER['REQUEST_URI'], '?');
    parse_str($_SERVER['QUERY_STRING'] ?? '', $params);
    unset($params['locale']);
    if (!empty($params)) {
        $url .= '?' . http_build_query($params);
    }
    header('Location: ' . $url);
    exit;
}

// Load questions configuration
$questions = require_once 'config.php';

// Load profiles and capture selected profile
$profiles = require_once 'profiles.php';
$selectedProfile = isset($_GET['profile']) ? $_GET['profile'] : 'balanced';

// Validate profile exists
if (!isset($profiles[$selectedProfile])) {
    $selectedProfile = 'balanced';
}

$profileData = $profiles[$selectedProfile];

// Handle custom weights if custom profile is selected
$customWeights = [];
if ($selectedProfile === 'custom') {
    foreach ($questions as $domainName => $domainData) {
        $paramName = 'weight_' . str_replace(' ', '_', $domainName);
        if (isset($_GET[$paramName])) {
            $weight = floatval($_GET[$paramName]);
            // Validate weight is between 1.0 and 2.0
            $customWeights[$domainName] = max(1.0, min(2.0, $weight));
        } else {
            $customWeights[$domainName] = 1.0;
        }
    }
}
?>
<!doctype html>
<html lang="<?php echo getLocale(); ?>" dir="<?php echo getTextDirection(); ?>" class="pf-theme-dark">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?php echo __e('assessment.title'); ?> - Viewfinder</title>

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
  <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
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
  </style>
</head>

<body>
  <!-- Language Selector -->
  <div class="language-selector" style="position: fixed; top: 20px; right: 20px; z-index: 1000;">
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

  <header class="pf-c-page__header">
    <div class="pf-c-page__header-brand">
      <div class="pf-c-page__header-brand-toggle"></div>
    </div>

    <div class="widget">
      <a href="../index.php"><button><i class="fa-solid fa-home"></i> <?php echo __e('common.home'); ?></button></a>
    </div>
  </header>

  <div class="container">
    <div class="qualifier-header">
      <h1><i class="fa-solid fa-clipboard-check"></i> <?php echo __e('assessment.title'); ?></h1>
      <p class="subtitle"><?php echo __e('assessment.subtitle'); ?></p>
      <div style="text-align: center; margin-top: 1rem; padding: 0.75rem; background: #1a1a1a; border-radius: 4px; border-left: 3px solid #0d60f8;">
        <i class="fa-solid <?php echo htmlspecialchars($profileData['icon']); ?>" style="color: #0d60f8; margin-right: 0.5rem;"></i>
        <strong style="color: #9ec7fc;"><?php echo __e('assessment.profile'); ?></strong>
        <span style="color: #ccc;"><?php echo __e($profileData['name_key']); ?></span>
      </div>
    </div>

    <div class="qualifier-intro" id="intro-section">
      <h3><i class="fa-solid fa-info-circle"></i> <?php echo __e('assessment.about_title'); ?></h3>
      <p><?php echo __e('assessment.about_description'); ?></p>
      <ul>
        <li><strong><?php echo __e('assessment.time_required'); ?></strong> <?php echo __e('assessment.time_value'); ?></li>
        <li><strong><?php echo __e('assessment.questions_count'); ?></strong> <?php echo __e('assessment.questions_value'); ?></li>
        <li><strong><?php echo __e('assessment.output'); ?></strong> <?php echo __e('assessment.output_value'); ?></li>
        <li><strong><?php echo __e('assessment.dont_know_hint'); ?></strong> <?php echo __e('assessment.dont_know_explanation'); ?></li>
      </ul>
    </div>

    <form action="results.php" method="POST" id="qualifier-form">
      <!-- Pass selected profile to results page -->
      <input type="hidden" name="profile" value="<?php echo htmlspecialchars($selectedProfile); ?>">

      <!-- Pass custom weights if using custom profile -->
      <?php if ($selectedProfile === 'custom'): ?>
        <?php foreach ($customWeights as $domain => $weight): ?>
          <input type="hidden" name="custom_weight_<?php echo htmlspecialchars(str_replace(' ', '_', $domain)); ?>" value="<?php echo htmlspecialchars($weight); ?>">
        <?php endforeach; ?>
      <?php endif; ?>

      <!-- Domain Questions -->
      <?php
      $sectionIndex = 0;
      foreach ($questions as $domainName => $domainData):
        $sectionIndex++;
      ?>
        <div class="domain-section section-pane"
             id="domain-<?php echo strtolower(str_replace(' ', '-', $domainName)); ?>"
             data-section="<?php echo $sectionIndex; ?>"
             style="display: <?php echo $sectionIndex === 1 ? 'block' : 'none'; ?>;">
          <div class="domain-header">
            <h2><i class="fa-solid fa-shield-halved"></i> <?php echo __e($domainData['name_key']); ?></h2>
            <p class="domain-description"><?php echo __e($domainData['description_key']); ?></p>
          </div>

          <div class="questions-list">
            <?php foreach ($domainData['questions'] as $question): ?>
              <div class="question-item">
                <div class="question-header">
                  <span class="question-text">
                    <?php echo __e($question['text_key']); ?>
                    <?php if (!empty($question['tooltip_key'])): ?>
                      <span class="tooltip-icon" data-tooltip="<?php echo htmlspecialchars(__($question['tooltip_key'])); ?>">
                        <i class="fa-solid fa-circle-info"></i>
                      </span>
                    <?php endif; ?>
                  </span>
                </div>
                <div class="button-group" data-domain="<?php echo $domainData['domain_key']; ?>">
                  <input type="radio"
                         id="<?php echo $question['id']; ?>-yes"
                         name="<?php echo $question['id']; ?>"
                         value="<?php echo $question['weight']; ?>"
                         class="question-radio">
                  <label for="<?php echo $question['id']; ?>-yes" class="btn-option btn-yes">
                    <i class="fa-solid fa-check"></i> <?php echo __e('common.yes'); ?>
                  </label>

                  <input type="radio"
                         id="<?php echo $question['id']; ?>-no"
                         name="<?php echo $question['id']; ?>"
                         value="0"
                         class="question-radio">
                  <label for="<?php echo $question['id']; ?>-no" class="btn-option btn-no">
                    <i class="fa-solid fa-xmark"></i> <?php echo __e('common.no'); ?>
                  </label>

                  <input type="radio"
                         id="<?php echo $question['id']; ?>-unknown"
                         name="<?php echo $question['id']; ?>"
                         value="unknown"
                         class="question-radio">
                  <label for="<?php echo $question['id']; ?>-unknown" class="btn-option btn-unknown">
                    <i class="fa-solid fa-question"></i> <?php echo __e('common.dont_know'); ?>
                  </label>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        </div>
      <?php endforeach; ?>

      <!-- Navigation Buttons -->
      <div class="form-navigation">
        <button type="button" id="prev-section" class="btn-secondary nav-button" style="display: none;">
          <i class="fa-solid fa-arrow-left"></i> <?php echo __e('assessment.button.previous'); ?>
        </button>
        <button type="button" id="next-section" class="btn-primary nav-button">
          <?php echo __e('assessment.button.next'); ?> <i class="fa-solid fa-arrow-right"></i>
        </button>
        <button type="submit" id="submit-form" class="btn-success nav-button" style="display: none;">
          <i class="fa-solid fa-chart-line"></i> <?php echo __e('assessment.button.generate_report'); ?>
        </button>
      </div>

      <!-- Reset Button -->
      <div class="form-reset">
        <button type="reset" class="btn-secondary btn-reset">
          <i class="fa-solid fa-rotate-left"></i> <?php echo __e('assessment.button.reset'); ?>
        </button>
      </div>
    </form>
  </div>

  <!-- Load DS Qualifier JavaScript -->
  <script src="js/ds-qualifier.js"></script>

  <script>
    // Language change function
    function changeLanguage(locale) {
      // Get current URL parameters
      const urlParams = new URLSearchParams(window.location.search);
      // Add locale parameter
      urlParams.set('locale', locale);
      // Redirect with locale parameter
      window.location.href = '?' + urlParams.toString();
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
