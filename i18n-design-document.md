# Internationalization (i18n) Design Document for Viewfinder Upstream

## 1. Project Overview

### 1.1 Application Overview
- **Project Name**: Viewfinder Upstream
- **Type**: Digital Sovereignty Readiness Assessment Tool
- **Technology Stack**:
  - Backend: PHP 8.1+
  - Frontend: jQuery 3.6.0, Bootstrap, PatternFly
  - PDF Generation: dompdf
  - Logging: monolog

### 1.2 Current Language Support Status
- Currently supports English only
- Hardcoded text throughout the application
- No language switching functionality

## 2. Internationalization Goals

### 2.1 Target Languages (Phase 1)
1. English (en) - Default language
2. Japanese (ja)
3. Additional languages can be added in the future with the same architecture

### 2.2 Internationalization Scope
- User Interface (UI)
- Assessment questions and descriptions
- Domain definitions and profiles
- Error messages
- PDF-generated reports
- Notification messages and alerts

## 3. Technical Design

### 3.1 Implementation Approaches

#### Option 1: PHP Native Implementation (Recommended)
- **Advantages**:
  - No external dependencies
  - Lightweight and simple
  - Easy integration with existing code
- **Implementation**:
  - Directory structure for translation files
  - Simple translation function implementation

#### Option 2: Gettext
- **Advantages**:
  - Standard internationalization solution
  - Extensive tool support
- **Disadvantages**:
  - Requires PHP extension
  - Complex configuration

### 3.2 Selected Implementation: PHP Native Implementation

#### Rationale:
1. Project is relatively small-scale
2. Minimizes external dependencies
3. Flexibility and maintainability
4. Minimal performance impact

## 4. File Structure Design

### 4.1 Directory Structure
```
viewfinder-upstream/
├── i18n/                           # New: Internationalization files
│   ├── I18n.php                   # Internationalization class
│   └── locales/                   # Translation files
│       ├── en.php                 # English translations
│       ├── ja.php                 # Japanese translations
│       └── ...                    # Other languages
├── ds-qualifier/
│   ├── config.php                 # Modified: Use translation keys
│   ├── profiles.php               # Modified: Use translation keys
│   ├── index.php                  # Modified: Use translation functions
│   ├── results.php                # Modified: Use translation functions
│   └── generate-pdf.php           # Modified: Use translation functions
├── error-pages/
│   └── templates/                 # Modified: Use translation functions
│       ├── file-not-found.php
│       └── ...
├── includes/
│   └── Config.php                 # Modified: Add default language setting
└── index.php                      # Modified: Use translation functions
```

### 4.2 Translation File Format

#### Example: i18n/locales/en.php
```php
<?php
return [
    // Landing Page
    'landing.title' => 'Digital Sovereignty Navigator',
    'landing.assessment.title' => 'Digital Sovereignty Readiness Assessment',
    'landing.assessment.description' => 'Quick 10-15 minute assessment to evaluate your organization\'s digital sovereignty readiness across 7 key domains',

    // Buttons
    'button.start_assessment' => 'Start Assessment',
    'button.home' => 'Home',
    'button.new_assessment' => 'New Assessment',
    'button.next' => 'Next',
    'button.previous' => 'Previous',
    'button.submit' => 'Complete Assessment',
    'button.reset' => 'Reset All Answers',

    // Domain Names
    'domain.data_sovereignty' => 'Data Sovereignty',
    'domain.technical_sovereignty' => 'Technical Sovereignty',
    'domain.operational_sovereignty' => 'Operational Sovereignty',
    'domain.assurance_sovereignty' => 'Assurance Sovereignty',
    'domain.open_source' => 'Open Source',
    'domain.executive_oversight' => 'Executive Oversight',
    'domain.managed_services' => 'Managed Services',

    // Domain Descriptions
    'domain.data_sovereignty.description' => 'Data control, residency, and encryption sovereignty',
    'domain.technical_sovereignty.description' => 'Technology independence and platform portability',
    // ... other domains

    // Questions
    'question.ds1.text' => 'Does your organization currently comply with all data residency requirements or regulations relevant to your country/region/vertical?',
    'question.ds1.tooltip' => 'Examples: GDPR (EU), PIPEDA (Canada), LGPD (Brazil), industry regulations requiring data to stay within specific jurisdictions.',
    // ... other questions

    // Maturity Levels
    'maturity.initial' => 'Initial',
    'maturity.managed' => 'Managed',
    'maturity.defined' => 'Defined',
    'maturity.quantitative' => 'Quantitatively Managed',
    'maturity.optimizing' => 'Optimizing',

    // Maturity Level Descriptions
    'maturity.initial.description' => 'Unpredictable, poorly controlled, reactive processes',
    'maturity.managed.description' => 'Projects planned and executed per policy, basic controls in place',
    // ... other maturity levels

    // Profiles
    'profile.balanced.name' => 'Balanced',
    'profile.balanced.description' => 'Equal weighting across all domains - suitable for general assessments and organizations without specific regulatory constraints.',
    'profile.financial.name' => 'Financial Services',
    'profile.financial.description' => 'Emphasizes data protection, audit controls, and compliance for banking and finance (PCI DSS, data residency, anti-money laundering).',
    // ... other profiles

    // Error Messages
    'error.validation.required' => 'Please answer all questions before proceeding.',
    'error.validation.unanswered' => 'You have {count} unanswered question(s) in this section.',
    'error.file_not_found.title' => 'Resource Not Found',
    'error.file_not_found.message' => 'The requested resource could not be found on the server.',

    // Notifications
    'notification.progress_saved' => 'Progress saved!',
    'notification.progress_restored' => 'Previous progress restored!',
    'notification.form_reset' => 'Form reset',
];
```

#### Example: i18n/locales/ja.php
```php
<?php
return [
    // Landing Page
    'landing.title' => 'デジタル主権ナビゲーター',
    'landing.assessment.title' => 'デジタル主権準備評価',
    'landing.assessment.description' => '7つの主要ドメインにおける組織のデジタル主権準備状況を評価する10〜15分の簡易評価',

    // Buttons
    'button.start_assessment' => '評価を開始',
    'button.home' => 'ホーム',
    'button.new_assessment' => '新規評価',
    'button.next' => '次へ',
    'button.previous' => '前へ',
    'button.submit' => '評価を完了',
    'button.reset' => 'すべての回答をリセット',

    // Domain Names
    'domain.data_sovereignty' => 'データ主権',
    'domain.technical_sovereignty' => '技術主権',
    'domain.operational_sovereignty' => '運用主権',
    'domain.assurance_sovereignty' => '保証主権',
    'domain.open_source' => 'オープンソース',
    'domain.executive_oversight' => '経営監督',
    'domain.managed_services' => 'マネージドサービス',

    // Domain Descriptions
    'domain.data_sovereignty.description' => 'データの管理、居住地、および暗号化の主権',
    'domain.technical_sovereignty.description' => '技術的独立性とプラットフォームの移植性',
    // ... other domains

    // Questions
    'question.ds1.text' => '貴組織は現在、貴国/地域/業界に関連するすべてのデータ居住要件または規制に準拠していますか？',
    'question.ds1.tooltip' => '例：GDPR（EU）、PIPEDA（カナダ）、LGPD（ブラジル）、特定の管轄区域内にデータを保持することを要求する業界規制。',
    // ... other questions

    // Maturity Levels
    'maturity.initial' => '初期',
    'maturity.managed' => '管理',
    'maturity.defined' => '定義',
    'maturity.quantitative' => '定量的管理',
    'maturity.optimizing' => '最適化',

    // Maturity Level Descriptions
    'maturity.initial.description' => '予測不可能で、管理が不十分で、反応的なプロセス',
    'maturity.managed.description' => 'ポリシーに従って計画・実行されるプロジェクト、基本的な管理体制',
    // ... other maturity levels

    // Profiles
    'profile.balanced.name' => 'バランス',
    'profile.balanced.description' => 'すべてのドメインに均等な重み付け - 一般的な評価や特定の規制要件のない組織に適しています。',
    'profile.financial.name' => '金融サービス',
    'profile.financial.description' => '銀行および金融業界のデータ保護、監査管理、コンプライアンスを重視（PCI DSS、データ居住地、マネーロンダリング対策）。',
    // ... other profiles

    // Error Messages
    'error.validation.required' => '次に進む前に、すべての質問に答えてください。',
    'error.validation.unanswered' => 'このセクションには{count}個の未回答の質問があります。',
    'error.file_not_found.title' => 'リソースが見つかりません',
    'error.file_not_found.message' => '要求されたリソースがサーバー上で見つかりませんでした。',

    // Notifications
    'notification.progress_saved' => '進行状況が保存されました！',
    'notification.progress_restored' => '以前の進行状況が復元されました！',
    'notification.form_reset' => 'フォームがリセットされました',
];
```

## 5. Implementation Design

### 5.1 Internationalization Class (i18n/I18n.php)

```php
<?php
/**
 * I18n - Internationalization Class
 * Handles translation and locale management for Viewfinder Upstream
 */
class I18n {
    private static $instance = null;
    private $locale = 'en';
    private $translations = [];
    private $fallbackLocale = 'en';
    private $availableLocales = ['en', 'ja'];

    /**
     * Private constructor for singleton pattern
     */
    private function __construct() {
        $this->locale = $this->detectLocale();
        $this->loadTranslations($this->locale);
    }

    /**
     * Get singleton instance
     * @return I18n
     */
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Set the current locale
     * @param string $locale
     */
    public function setLocale($locale) {
        if ($this->isValidLocale($locale)) {
            $this->locale = $locale;
            $this->loadTranslations($locale);
        }
    }

    /**
     * Get the current locale
     * @return string
     */
    public function getLocale() {
        return $this->locale;
    }

    /**
     * Detect locale from various sources
     * Priority: 1) Session, 2) Cookie, 3) Browser, 4) Default
     * @return string
     */
    private function detectLocale() {
        // 1. Check session
        if (isset($_SESSION['locale']) && $this->isValidLocale($_SESSION['locale'])) {
            return $_SESSION['locale'];
        }

        // 2. Check cookie
        if (isset($_COOKIE['locale']) && $this->isValidLocale($_COOKIE['locale'])) {
            return $_COOKIE['locale'];
        }

        // 3. Check browser language
        if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
            $browserLang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
            if ($this->isValidLocale($browserLang)) {
                return $browserLang;
            }
        }

        // 4. Return default
        return $this->fallbackLocale;
    }

    /**
     * Validate if locale is supported
     * @param string $locale
     * @return bool
     */
    private function isValidLocale($locale) {
        return in_array($locale, $this->availableLocales);
    }

    /**
     * Load translations for a specific locale
     * @param string $locale
     */
    private function loadTranslations($locale) {
        $file = __DIR__ . "/locales/{$locale}.php";

        if (file_exists($file)) {
            $this->translations = require $file;
        } else {
            // Load fallback language
            $fallbackFile = __DIR__ . "/locales/{$this->fallbackLocale}.php";
            if (file_exists($fallbackFile)) {
                $this->translations = require $fallbackFile;
            }
        }
    }

    /**
     * Translate a key with optional parameters
     * @param string $key Translation key
     * @param array $params Parameters for replacement
     * @return string Translated string
     */
    public function translate($key, $params = []) {
        $translation = $this->translations[$key] ?? $key;

        // Replace parameters
        foreach ($params as $placeholder => $value) {
            $translation = str_replace("{{$placeholder}}", $value, $translation);
        }

        return $translation;
    }

    /**
     * Get list of available locales
     * @return array
     */
    public function getAvailableLocales() {
        return $this->availableLocales;
    }

    /**
     * Get locale name for display
     * @param string $locale
     * @return string
     */
    public function getLocaleName($locale) {
        $names = [
            'en' => 'English',
            'ja' => '日本語',
        ];
        return $names[$locale] ?? $locale;
    }
}

/**
 * Global translation function
 * @param string $key Translation key
 * @param array $params Parameters for replacement
 * @return string Translated string
 */
function __($key, $params = []) {
    return I18n::getInstance()->translate($key, $params);
}

/**
 * Global translation function with HTML escaping
 * @param string $key Translation key
 * @param array $params Parameters for replacement
 * @return string HTML-escaped translated string
 */
function __e($key, $params = []) {
    return htmlspecialchars(__($key, $params), ENT_QUOTES, 'UTF-8');
}

/**
 * Set the current locale
 * @param string $locale
 */
function setLocale($locale) {
    I18n::getInstance()->setLocale($locale);
    $_SESSION['locale'] = $locale;
    setcookie('locale', $locale, time() + (86400 * 365), '/');
}

/**
 * Get the current locale
 * @return string
 */
function getLocale() {
    return I18n::getInstance()->getLocale();
}

/**
 * Get available locales
 * @return array
 */
function getAvailableLocales() {
    return I18n::getInstance()->getAvailableLocales();
}

/**
 * Get locale name for display
 * @param string $locale
 * @return string
 */
function getLocaleName($locale) {
    return I18n::getInstance()->getLocaleName($locale);
}
```

### 5.2 Code Modification Examples

#### Before (index.php):
```php
<h1 style="color: #9ec7fc; font-size: 2rem;">
    Digital Sovereignty Navigator
</h1>
```

#### After (index.php):
```php
<?php
session_start();
require_once __DIR__ . '/i18n/I18n.php';

// Handle locale change
if (isset($_GET['locale'])) {
    setLocale($_GET['locale']);
    header('Location: ' . strtok($_SERVER['REQUEST_URI'], '?'));
    exit;
}
?>
<h1 style="color: #9ec7fc; font-size: 2rem;">
    <?php echo __e('landing.title'); ?>
</h1>
```

#### Before (ds-qualifier/config.php):
```php
'Data Sovereignty' => [
    'description' => 'Data control, residency, and encryption sovereignty',
    'questions' => [
        [
            'text' => 'Does your organization currently comply...',
            'tooltip' => 'Examples: GDPR (EU), PIPEDA (Canada)...'
        ]
    ]
]
```

#### After (ds-qualifier/config.php):
```php
<?php
require_once __DIR__ . '/../i18n/I18n.php';

return [
    'Data Sovereignty' => [
        'name_key' => 'domain.data_sovereignty',
        'description_key' => 'domain.data_sovereignty.description',
        'domain_key' => 'Domain-1',
        'questions' => [
            [
                'id' => 'ds1',
                'text_key' => 'question.ds1.text',
                'tooltip_key' => 'question.ds1.tooltip',
                'weight' => 1
            ]
        ]
    ]
    // ... other domains
];
```

#### Usage in templates:
```php
// In index.php or other pages
<?php foreach ($questions as $domainKey => $domainData): ?>
    <h2><?php echo __e($domainData['name_key']); ?></h2>
    <p><?php echo __e($domainData['description_key']); ?></p>

    <?php foreach ($domainData['questions'] as $question): ?>
        <div class="question">
            <?php echo __e($question['text_key']); ?>
            <span class="tooltip">
                <?php echo __e($question['tooltip_key']); ?>
            </span>
        </div>
    <?php endforeach; ?>
<?php endforeach; ?>
```

## 6. Language Switching Feature

### 6.1 Language Selector UI

Add language selector to header:

```php
<!-- Language Selector Component -->
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

<script>
function changeLanguage(locale) {
    // Save form data if needed
    if (typeof saveProgress === 'function') {
        saveProgress();
    }

    // Change language and reload
    const currentUrl = new URL(window.location.href);
    currentUrl.searchParams.set('locale', locale);
    window.location.href = currentUrl.toString();
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
```

### 6.2 Locale Change Handler

Add to the beginning of each PHP page:

```php
<?php
session_start();
require_once __DIR__ . '/i18n/I18n.php';

// Handle locale change request
if (isset($_GET['locale'])) {
    setLocale($_GET['locale']);
    // Remove locale parameter and redirect
    $url = strtok($_SERVER['REQUEST_URI'], '?');
    if (!empty($_SERVER['QUERY_STRING'])) {
        parse_str($_SERVER['QUERY_STRING'], $params);
        unset($params['locale']);
        if (!empty($params)) {
            $url .= '?' . http_build_query($params);
        }
    }
    header('Location: ' . $url);
    exit;
}
?>
```

## 7. Key Areas Requiring Internationalization

### 7.1 Landing Page (index.php)
- Page title
- Navigation items
- Assessment card description
- Profile selection UI
- Domain weight display
- CMMI maturity levels
- Disclaimer text

**Estimated Translation Keys**: ~40

### 7.2 Assessment Page (ds-qualifier/index.php)
- Page title
- Instructions
- Domain names and descriptions
- Question text
- Tooltips
- Button labels (Next, Previous, Submit, Reset)

**Estimated Translation Keys**: ~30 + 21 questions + 21 tooltips = ~72

### 7.3 Questions Configuration (ds-qualifier/config.php)
- All 21 question texts
- All 21 tooltips
- 7 domain descriptions

**Estimated Translation Keys**: ~49 (already counted above)

### 7.4 Profiles (ds-qualifier/profiles.php)
- Profile names (Balanced, Financial, Healthcare, Government, Technology, Manufacturing, Telecommunications, Energy, Custom)
- Profile descriptions

**Estimated Translation Keys**: ~18 (9 names + 9 descriptions)

### 7.5 Results Page (ds-qualifier/results.php)
- Page title
- Maturity level names
- Recommendations
- Domain analysis table headers
- Improvement actions list
- "Don't Know" questions section

**Estimated Translation Keys**: ~50

### 7.6 PDF Generation (ds-qualifier/generate-pdf.php)
- All PDF text content
- Same content as results page

**Estimated Translation Keys**: Reuses results page keys

### 7.7 Error Pages (error-pages/templates/)
- Error messages
- Error descriptions
- Button labels

**Estimated Translation Keys**: ~20

### 7.8 JavaScript (ds-qualifier/js/ds-qualifier.js)
- Alert messages
- Notification messages
- Validation messages

**Estimated Translation Keys**: ~15

**Total Estimated Translation Keys**: ~250-300 keys

## 8. Translation Key Naming Conventions

### 8.1 Naming Pattern
```
{category}.{subcategory}.{element}
```

### 8.2 Categories:
- `landing.*` - Landing page elements
- `domain.*` - Domain-related content
- `question.*` - Assessment questions
- `maturity.*` - Maturity level content
- `profile.*` - Profile-related content
- `button.*` - Button labels
- `error.*` - Error messages
- `notification.*` - Notification messages
- `validation.*` - Validation messages
- `common.*` - Common/shared elements

### 8.3 Examples:
```
landing.title
landing.assessment.title
landing.assessment.description

domain.data_sovereignty
domain.data_sovereignty.description

question.ds1.text
question.ds1.tooltip

maturity.initial
maturity.initial.title
maturity.initial.description
maturity.initial.recommendation

profile.balanced.name
profile.balanced.description

button.start_assessment
button.next
button.previous
button.submit

error.file_not_found.title
error.file_not_found.message

notification.progress_saved
notification.form_reset

validation.required
validation.unanswered_count
```

## 9. Implementation Phases

### Phase 1: Foundation (1-2 weeks)
**Tasks**:
1. Create I18n class (`i18n/I18n.php`)
2. Set up translation file structure
3. Create English translation file (`i18n/locales/en.php`)
4. Extract all hardcoded strings and create translation keys
5. Implement basic language switching functionality
6. Add language selector to header

**Deliverables**:
- Working I18n system
- Complete English translation file
- Language selector UI

### Phase 2: Page-by-Page Implementation (2-3 weeks)
**Tasks**:
1. Internationalize landing page (`index.php`)
2. Internationalize assessment page (`ds-qualifier/index.php`)
3. Internationalize results page (`ds-qualifier/results.php`)
4. Internationalize PDF generation (`ds-qualifier/generate-pdf.php`)
5. Internationalize error pages (`error-pages/templates/`)
6. Update JavaScript for i18n support

**Deliverables**:
- All pages using translation keys
- Functional language switching on all pages

### Phase 3: Japanese Translation (1-2 weeks)
**Tasks**:
1. Translate all keys to Japanese
2. Review and refine translations
3. Test Japanese display in all contexts
4. Ensure PDF generation works with Japanese text
5. Adjust layouts if needed for Japanese text length

**Deliverables**:
- Complete Japanese translation file (`i18n/locales/ja.php`)
- Tested Japanese language support

### Phase 4: Testing and Optimization (1 week)
**Tasks**:
1. Comprehensive testing of all pages in both languages
2. UI/UX verification
3. Performance testing
4. Bug fixes
5. Documentation updates

**Deliverables**:
- Fully tested bilingual application
- Updated documentation

## 10. Technical Considerations

### 10.1 Character Encoding
- Use UTF-8 throughout
- Set UTF-8 in all PHP files and HTML
- If using database, use UTF-8 collation

```php
// At the beginning of each PHP file
header('Content-Type: text/html; charset=utf-8');
```

```html
<!-- In HTML head -->
<meta charset="utf-8">
```

### 10.2 Date and Time Formatting

```php
/**
 * Format date according to locale
 * @param int $timestamp
 * @param string $format 'long' or 'short'
 * @return string
 */
function formatDate($timestamp, $format = 'long') {
    $locale = getLocale();

    $formats = [
        'en' => [
            'long' => 'F j, Y \a\t g:i A',
            'short' => 'm/d/Y'
        ],
        'ja' => [
            'long' => 'Y年n月j日 H:i',
            'short' => 'Y/m/d'
        ]
    ];

    return date($formats[$locale][$format] ?? $formats['en'][$format], $timestamp);
}
```

### 10.3 Number Formatting

```php
/**
 * Format number according to locale
 * @param float $number
 * @param int $decimals
 * @return string
 */
function formatNumber($number, $decimals = 0) {
    $locale = getLocale();

    $formats = [
        'en' => ['decimal' => '.', 'thousands' => ','],
        'ja' => ['decimal' => '.', 'thousands' => ',']
    ];

    $format = $formats[$locale] ?? $formats['en'];
    return number_format($number, $decimals, $format['decimal'], $format['thousands']);
}
```

### 10.4 Text Direction (LTR/RTL)
- Currently supporting LTR languages only
- Future RTL language support (Arabic, Hebrew) would require CSS adjustments

```php
/**
 * Get text direction for locale
 * @param string $locale
 * @return string 'ltr' or 'rtl'
 */
function getTextDirection($locale = null) {
    $locale = $locale ?? getLocale();

    $rtlLocales = ['ar', 'he', 'fa', 'ur'];
    return in_array($locale, $rtlLocales) ? 'rtl' : 'ltr';
}
```

```html
<html lang="<?php echo getLocale(); ?>" dir="<?php echo getTextDirection(); ?>">
```

### 10.5 PDF Generation with Multilingual Support

```php
// In generate-pdf.php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../i18n/I18n.php';

use Dompdf\Dompdf;
use Dompdf\Options;

$options = new Options();
$options->set('isHtml5ParserEnabled', true);
$options->set('isRemoteEnabled', true);

// Set font based on locale
$locale = getLocale();
if ($locale === 'ja') {
    // Use Japanese-compatible font
    $options->set('defaultFont', 'DejaVu Sans');
    // Or use a specific Japanese font if available:
    // $options->set('defaultFont', 'ipagp'); // IPA Gothic
} else {
    $options->set('defaultFont', 'DejaVu Sans');
}

$dompdf = new Dompdf($options);
```

**Note**: For proper Japanese font support in PDFs:
1. Install Japanese fonts in dompdf
2. Update font configuration
3. Consider using web fonts or embedding fonts

### 10.6 JavaScript Internationalization

For JavaScript messages, create a simple approach:

```php
<!-- In HTML head -->
<script>
const i18n = <?php echo json_encode([
    'validation.required' => __('validation.required'),
    'validation.unanswered' => __('validation.unanswered'),
    'notification.progress_saved' => __('notification.progress_saved'),
    'notification.progress_restored' => __('notification.progress_restored'),
    'notification.form_reset' => __('notification.form_reset'),
]); ?>;

function __(key, params = {}) {
    let translation = i18n[key] || key;
    for (const [placeholder, value] of Object.entries(params)) {
        translation = translation.replace(`{${placeholder}}`, value);
    }
    return translation;
}
</script>
```

Then in JavaScript:
```javascript
// Before
alert('Please answer all questions before proceeding.');

// After
alert(__('validation.required'));

// With parameters
alert(__('validation.unanswered', {count: unanswered}));
```

## 11. Security Considerations

### 11.1 XSS Prevention
Always escape output when displaying translations:

```php
// Use __e() for HTML context
<h1><?php echo __e('landing.title'); ?></h1>

// Use __() for JavaScript context (with proper escaping)
<script>
const message = <?php echo json_encode(__('notification.saved')); ?>;
</script>
```

### 11.2 Locale Validation
Validate locale input to prevent path traversal attacks:

```php
private function isValidLocale($locale) {
    // Only allow whitelisted locales
    return in_array($locale, $this->availableLocales);
}
```

### 11.3 File Path Security
Prevent directory traversal in translation file loading:

```php
private function loadTranslations($locale) {
    // Validate locale first
    if (!$this->isValidLocale($locale)) {
        $locale = $this->fallbackLocale;
    }

    // Use realpath to prevent traversal
    $file = __DIR__ . "/locales/{$locale}.php";
    $realFile = realpath($file);
    $basePath = realpath(__DIR__ . '/locales/');

    if ($realFile && strpos($realFile, $basePath) === 0 && file_exists($realFile)) {
        $this->translations = require $realFile;
    }
}
```

## 12. Testing Plan

### 12.1 Functional Testing
- [ ] Language switching works on all pages
- [ ] Session/cookie persistence of language selection
- [ ] Browser language auto-detection works
- [ ] PDF generation uses correct language
- [ ] Error pages display in correct language
- [ ] Form submission preserves language selection
- [ ] JavaScript notifications in correct language

### 12.2 Display Testing
- [ ] Japanese text displays correctly
- [ ] No layout breaking with Japanese text
- [ ] Buttons and labels are properly sized
- [ ] PDF renders Japanese correctly
- [ ] Special characters display properly

### 12.3 UX Testing
- [ ] Language switching is intuitive
- [ ] Form input is preserved on language change
- [ ] Translations are natural and understandable
- [ ] No missing translations (fallback to English works)

### 12.4 Performance Testing
- [ ] Page load time with translations
- [ ] Memory usage
- [ ] Translation loading efficiency

### 12.5 Cross-Browser Testing
- [ ] Chrome
- [ ] Firefox
- [ ] Safari
- [ ] Edge

## 13. Maintenance Guidelines

### 13.1 Adding New Translations
1. Add new key to `i18n/locales/en.php`
2. Add translations to all supported language files
3. Use the new key in code with `__()` or `__e()`
4. Test in all languages

### 13.2 Updating Translations
1. Locate the key in language files
2. Update the translation
3. Test the change
4. Ensure consistency across languages

### 13.3 Adding New Languages
1. Create new file `i18n/locales/{locale}.php`
2. Copy structure from `en.php`
3. Translate all keys
4. Add locale to `$availableLocales` in `I18n.php`
5. Add locale name to `getLocaleName()` method
6. Test thoroughly

### 13.4 Translation File Organization
Keep translation files organized by:
- Grouping related keys together
- Adding comments for context
- Using consistent formatting

Example:
```php
<?php
return [
    // ========================================
    // Landing Page
    // ========================================
    'landing.title' => 'Digital Sovereignty Navigator',
    'landing.subtitle' => 'Assess your organization\'s sovereignty readiness',

    // ========================================
    // Buttons - Common across all pages
    // ========================================
    'button.start' => 'Start',
    'button.cancel' => 'Cancel',

    // ... etc
];
```

## 14. Performance Optimization

### 14.1 Translation Caching (Optional)
For production environments with high traffic:

```php
private function loadTranslations($locale) {
    $cacheKey = "i18n_translations_{$locale}";

    // Try to load from cache (APCu example)
    if (function_exists('apcu_fetch')) {
        $cached = apcu_fetch($cacheKey);
        if ($cached !== false) {
            $this->translations = $cached;
            return;
        }
    }

    // Load from file
    $file = __DIR__ . "/locales/{$locale}.php";
    if (file_exists($file)) {
        $this->translations = require $file;

        // Store in cache (1 hour TTL)
        if (function_exists('apcu_store')) {
            apcu_store($cacheKey, $this->translations, 3600);
        }
    }
}
```

### 14.2 Lazy Loading
Translation files are only loaded once per request when I18n is instantiated.

### 14.3 Production vs Development
```php
// In Config.php
const IS_PRODUCTION = true; // Set based on environment

// Use caching only in production
if (IS_PRODUCTION && function_exists('apcu_fetch')) {
    // Use cache
}
```

## 15. Documentation

### 15.1 Developer Documentation
Add to README.md:

```markdown
## Internationalization (i18n)

This application supports multiple languages. Currently supported:
- English (en) - Default
- Japanese (ja)

### Using Translations in Code

#### PHP
```php
// Include i18n
require_once __DIR__ . '/i18n/I18n.php';

// Simple translation
echo __('landing.title');

// Translation with HTML escaping (recommended for HTML output)
echo __e('landing.title');

// Translation with parameters
echo __('validation.unanswered', ['count' => 5]);
```

#### JavaScript
```javascript
// Translations are available via global i18n object
alert(__('notification.saved'));

// With parameters
alert(__('validation.unanswered', {count: 5}));
```

### Adding New Translations

1. Add key to `i18n/locales/en.php`:
```php
'my.new.key' => 'My new translation',
```

2. Add to all language files (`ja.php`, etc.)

3. Use in code:
```php
echo __e('my.new.key');
```

### Adding New Languages

1. Create `i18n/locales/{locale}.php`
2. Translate all keys from `en.php`
3. Update `I18n.php`:
   - Add locale to `$availableLocales`
   - Add locale name to `getLocaleName()`
```

### 15.2 Translator Documentation
Create `i18n/TRANSLATION_GUIDE.md`:

```markdown
# Translation Guide for Viewfinder Upstream

## Overview
This document provides guidelines for translating Viewfinder Upstream.

## Translation Keys
Translation keys use dot notation:
- `category.subcategory.element`

## Context Information

### Domain Names
- `domain.*` - Seven sovereignty domains
- Keep technical terms consistent

### Maturity Levels
- `maturity.*` - CMMI maturity levels (Initial, Managed, Defined, Quantitatively Managed, Optimizing)
- These are standard CMMI terms - use official translations if available

### Questions
- `question.*` - Assessment questions
- Maintain professional, formal tone
- Technical accuracy is critical

## Style Guidelines

### Tone
- Professional and formal
- Neutral (avoid gender-specific language)
- Clear and concise

### Formatting
- Preserve HTML entities
- Keep placeholder syntax: `{placeholder}`
- Maintain line breaks where present

### Technical Terms
Keep these terms in English or use standard translations:
- GDPR, HIPAA, PCI DSS
- Kubernetes, API
- Cloud, Data Center

## Questions?
Contact: [your contact information]
```

## 16. Future Enhancements

### 16.1 Additional Languages
Potential languages to support:
- French (fr)
- German (de)
- Spanish (es)
- Chinese Simplified (zh-CN)
- Korean (ko)
- Portuguese (pt)

### 16.2 Advanced Features
- **Pluralization Support**:
```php
function __n($singular, $plural, $count, $params = []) {
    $key = ($count === 1) ? $singular : $plural;
    return __($key, array_merge($params, ['count' => $count]));
}

// Usage
echo __n('question.one', 'question.many', $count);
```

- **Date/Time Localization with IntlDateFormatter**:
```php
$fmt = new IntlDateFormatter(
    getLocale(),
    IntlDateFormatter::LONG,
    IntlDateFormatter::SHORT
);
echo $fmt->format(time());
```

- **Currency Formatting**:
```php
$fmt = new NumberFormatter(getLocale(), NumberFormatter::CURRENCY);
echo $fmt->formatCurrency(1234.56, 'USD');
```

- **Locale-Specific Content**:
  - Different images for different locales
  - Locale-specific examples in questions
  - Regional compliance variations

### 16.3 Translation Management Tools
Consider integrating with:
- POEditor
- Crowdin
- Transifex
- Lokalise

These tools can:
- Facilitate collaboration with translators
- Track translation progress
- Provide translation memory
- Enable community contributions

## 17. Migration Strategy

### 17.1 Gradual Migration Approach
1. **Phase 1**: Set up infrastructure
   - Create I18n class
   - Create translation files
   - Add language selector

2. **Phase 2**: Extract strings page-by-page
   - Start with landing page
   - Move to assessment pages
   - Then results and PDF
   - Finally error pages

3. **Phase 3**: Translate to Japanese
   - Professional translation service recommended
   - Technical review by domain expert
   - User testing with Japanese speakers

4. **Phase 4**: Polish and optimize
   - Fix any layout issues
   - Optimize performance
   - Update documentation

### 17.2 Backward Compatibility
During migration, both hardcoded and translated strings may coexist:

```php
// Fallback approach during migration
function __safe($key, $default = null) {
    $translation = __($key);
    // If translation equals key (not found), use default
    return ($translation === $key && $default !== null) ? $default : $translation;
}

// Usage
echo __safe('landing.title', 'Digital Sovereignty Navigator');
```

## 18. Cost and Resource Estimates

### 18.1 Development Time
- Phase 1 (Foundation): 40-60 hours
- Phase 2 (Implementation): 60-80 hours
- Phase 3 (Japanese Translation): 20-30 hours
- Phase 4 (Testing): 20-30 hours
- **Total**: 140-200 hours

### 18.2 Translation Costs
For professional translation services:
- **Word Count**: Approximately 3,000-4,000 words
- **Cost Range**: $0.10-$0.25 per word for EN→JA
- **Estimated Cost**: $300-$1,000 per language

Alternatively:
- Use bilingual team members
- Community contributions (for open source)
- Machine translation + human review (faster, lower quality)

## 19. Success Metrics

### 19.1 Technical Metrics
- [ ] 100% of user-facing strings internationalized
- [ ] Zero translation-related errors
- [ ] Page load time impact < 5%
- [ ] All tests passing in all languages

### 19.2 User Experience Metrics
- [ ] Language switching success rate
- [ ] User satisfaction with translations
- [ ] Assessment completion rate by language
- [ ] Error report reduction in non-English languages

## 20. Summary

This internationalization design provides a comprehensive roadmap for making Viewfinder Upstream multilingual. The approach balances:

### Key Strengths:
1. **Simplicity**: Native PHP implementation without heavy dependencies
2. **Flexibility**: Easy to add new languages
3. **Maintainability**: Clear structure and documentation
4. **Performance**: Minimal overhead
5. **Security**: Proper validation and escaping

### Implementation Approach:
- Phased rollout minimizes risk
- Clear separation of content and code
- Consistent naming conventions
- Comprehensive testing plan

### Next Steps:
1. Review and approve this design document
2. Set up development environment
3. Begin Phase 1 implementation
4. Regular progress reviews
5. Stakeholder feedback incorporation

### Long-term Vision:
- Support for multiple languages
- Community-driven translations
- Continuous improvement based on user feedback
- Industry-leading multilingual sovereignty assessment tool

---

**Document Version**: 1.0
**Last Updated**: 2025-05-16
**Author**: Development Team
**Status**: Draft for Review
