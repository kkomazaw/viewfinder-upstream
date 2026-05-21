# [PROPOSAL] Add Internationalization Support and Japanese Language

## Summary

This proposal adds complete internationalization (i18n) infrastructure to the Viewfinder assessment tool, starting with English and Japanese language support. The work is split into two focused PRs for easier review.

## Motivation

Digital sovereignty is a critical concern for organizations worldwide. Supporting multiple languages enables:
- 🌏 Broader adoption across international markets, especially Japan and Asia-Pacific
- 📊 More accurate assessments when conducted in users' native languages
- 🎯 Better engagement with non-English-speaking stakeholders
- 📄 Professional PDF reports in multiple languages
- 🏗️ Better code maintainability through translation key abstraction

## Current State

The application currently has:
- ❌ **No i18n infrastructure:** All text is hardcoded in English
- ❌ **No translation system:** No `__()` function or locale management
- ❌ **No locale switching:** Cannot change languages
- ❌ **No font support for non-Latin scripts**

## Proposed Changes

### Overview

I propose **two sequential PRs** to upstream these changes:

```
PR #1: i18n Infrastructure + English
   ↓
PR #2: Japanese Language Support
```

### PR #1: Implement i18n Infrastructure with English Translations

**What:** Add complete i18n system with all English text converted to translation keys
**Why:** Enables future translation to any language while maintaining current English functionality
**Impact:** Zero visual changes for users, but complete architectural improvement

**Changed Files:**
- `i18n/I18n.php` (new file - i18n core system)
- `i18n/locales/en.php` (new file - 330+ English translation keys)
- `ds-qualifier/generate-pdf.php` (use `__()` function throughout)
- `ds-qualifier/results.php` (use `__()` function throughout)
- `ds-qualifier/question.php` (use `__()` function throughout)
- `index.php` (use `__()` function throughout)
- All other PHP files with user-facing text

**New i18n System Features:**
```php
// Locale detection and switching
function getLocale(): string  // Detects from URL parameter, session, or browser
function setLocale(string $locale): void  // Sets current locale

// Translation functions
function __(string $key, array $params = [], string $default = ''): string
function __e(string $key, array $params = [], string $default = ''): void  // Echo variant
```

**Example Changes:**
```php
// Before
<h4>Requirements Identified:</h4>
<p>Digital Sovereignty Readiness Assessment</p>

// After
<h4><?php echo __('results.domain_insights.requirements_identified'); ?></h4>
<p><?php echo __('app.title'); ?></p>
```

**Translation Key Organization:**
```
app.*                    - Application-wide strings (title, navigation, etc.)
maturity.*              - Maturity level names and descriptions
improvement.*           - Improvement actions (5 levels × ~12 keys each)
results.*               - Results page sections and labels
profile.*               - Profile names and descriptions
error.*                 - Error messages
pdf.*                   - PDF-specific strings
```

**Benefits:**
- ✅ Complete i18n infrastructure for any future language
- ✅ Better code maintainability (no hardcoded strings)
- ✅ Zero breaking changes (all English text preserved exactly)
- ✅ Easy to review (pure refactoring + new i18n system)
- ✅ Enables locale switching mechanism
- ✅ Sets foundation for Japanese and other languages

---

### PR #2: Add Japanese Language Support with Font Rendering

**What:** Add complete Japanese translations and font rendering fixes
**Why:** Enables full Japanese language support with professional PDF output
**Impact:** Adds Japanese language option, no changes to English

**Changed Files:**
- `i18n/locales/ja.php` (new file - 330+ Japanese translation keys)
- `generate-font-metrics.php` (new file - font metrics extraction)
- `ds-qualifier/generate-pdf.php` (locale-specific CSS and font handling)
- `Dockerfile` (Japanese font installation and metrics generation)

**Japanese Translation Quality:**
| English | Japanese |
|---------|----------|
| Critical Actions for Initial Level | 初期レベルの重要なアクション |
| Requirements Identified: | 特定された要件： |
| Domain Insights | ドメインインサイト |
| Digital Sovereignty Readiness Assessment | デジタル主権準備状況評価 |

**Font Rendering Problem & Solution:**

Without proper font metrics, Japanese PDFs have serious issues:
```
❌ Text overflows margins
❌ Characters overlap
❌ Breaks mid-character: "日本|語" instead of "日本語"
❌ Missing glyphs (boxes instead of characters)
```

Our solution:

1. **Extract font metrics** from IPA Gothic TrueType font
   - 11,691 character widths mapped to Unicode values
   - Generate Adobe Font Metrics (AFM) file during build
   - One-time build operation, no runtime cost

2. **Add locale-specific CSS**
   ```php
   if ($locale === 'ja') {
       $wordBreak = 'break-all';      // CJK standard line breaking
       $overflowWrap = 'anywhere';     // Prevent overflow
   } else {
       $wordBreak = 'normal';          // English word boundaries
       $overflowWrap = 'auto';
   }
   ```

3. **Optimize PDF layout**
   - Dynamic table layout (`auto` instead of `fixed`)
   - Adjusted font sizes for better Japanese rendering
   - Prevent container overflow with proper box-sizing

**Benefits:**
- ✅ Complete Japanese UI and PDF support
- ✅ Professional-quality Japanese PDFs
- ✅ Accurate text layout and line breaking
- ✅ No impact on English functionality
- ✅ Extensible to other CJK languages (Chinese, Korean)

**Technical Details:**

The font metrics extraction:
```php
TrueType Font (2048 units/em)
    ↓
FontLib Parser
    ↓
Unicode Character Map (11,691 chars)
    ↓
Horizontal Metrics (advance width for each char)
    ↓
AFM File (scaled to 1000 units/em)
    ↓
Dompdf (accurate rendering)
```

---

## Testing Strategy

Each PR includes comprehensive testing:

### PR #1 Testing (i18n Infrastructure)
```bash
# Verify i18n system works
php -r "require 'i18n/I18n.php'; echo __('app.title');"
# Expected: "Digital Sovereignty Readiness Assessment"

# English output should be identical to before
# Compare screenshots/PDFs before and after PR
# Should show: zero visual differences

# Locale switching works
curl 'http://localhost:8080/?lang=en' | grep "Digital Sovereignty"
# Should work (only English available in PR #1)

# All translation keys are used
grep -r "__(" --include="*.php" | wc -l
# Should match number of translation key usages
```

### PR #2 Testing (Japanese Language)
```bash
# Japanese displays correctly
curl 'http://localhost:8080/?lang=ja' | grep "デジタル主権"
# Expected: Find Japanese text

# English still works perfectly
curl 'http://localhost:8080/?lang=en' | grep "Digital Sovereignty"
# Expected: Find English text

# Font metrics generated during build
podman run --rm app cat vendor/dompdf/dompdf/lib/fonts/ipaexg.ufm | wc -l
# Expected: 11,712 lines (11,691 chars + headers)

# Japanese PDF test (manual)
# 1. Set locale to Japanese
# 2. Generate PDF
# 3. Verify no text overflow
# 4. Verify proper line breaks at character boundaries
# 5. Check character spacing and alignment
# 6. Confirm all glyphs render correctly

# English PDF regression test
# 1. Generate English PDF
# 2. Compare with pre-PR PDF
# 3. Should be identical
```

## Design Document

Full technical details available in: [`docs/i18n-design-and-pr-plan.md`](./i18n-design-and-pr-plan.md)

Includes:
- Architecture diagrams
- Translation key conventions
- Font metrics extraction algorithm
- Performance analysis
- Security considerations
- Future extensibility plan

## Questions for Maintainers

Before I prepare the PRs, I'd appreciate feedback on:

1. **PR Sequencing:** Is the 2-PR approach acceptable?
   - PR #1: Complete i18n infrastructure + English translations
   - PR #2: Japanese language support (translations + font rendering)
   - Would you prefer different grouping or a single large PR?

2. **i18n System Design:** The proposed i18n system uses:
   - PHP session-based locale storage
   - URL parameter and browser language detection
   - Simple key-based translation lookup
   - Is this approach acceptable, or would you prefer a different i18n library/approach?

3. **Translation Quality:** Should I have the Japanese translations professionally reviewed before PR #2?

4. **Font Licensing:** IPA Gothic font is under [IPA Font License](https://opensource.org/licenses/IPA) (OSI-approved, permissive). Is this acceptable for this project?

5. **Scope:** Are there any additional i18n improvements you'd like included? Examples:
   - Date/time formatting per locale
   - Number formatting per locale
   - Currency formatting
   - RTL (right-to-left) support framework for Arabic/Hebrew
   - Pluralization rules

6. **Documentation:** What level of documentation would you like for each PR? (e.g., inline comments, README updates, migration guide, i18n contribution guide for future translators)

## Benefits to Upstream

- 🌍 **Broader reach:** Access to Japanese market
- 🏗️ **Better architecture:** Translation keys instead of hardcoded strings
- 📚 **Reusable infrastructure:** Easy to add more languages
- 🎨 **Improved PDF engine:** Better handling of non-Latin scripts
- ✅ **Production-tested:** Already deployed and working

## Backward Compatibility

- ✅ No breaking changes
- ✅ English remains default locale
- ✅ No configuration changes required
- ✅ No database migrations needed

## Timeline

If approved, I can prepare PRs on this schedule:

- **Week 1:** PR #1 (i18n Infrastructure) ready for review
- **Week 2-3:** PR #1 review cycle and merge
- **Week 4:** PR #2 (Japanese Language) ready for review (after PR #1 merge)
- **Week 5-6:** PR #2 review cycle and merge

**Total estimated time:** 5-6 weeks for complete i18n + Japanese support

## Sample Screenshots

### Before (English)
```
Recommended Improvement Actions
├─ Critical Actions for Initial Level
│  └─ Gain Executive Awareness: Educate leadership...
└─ Requirements Identified:
   └─ Data residency requirements
```

### After (Japanese)
```
推奨される改善アクション
├─ 初期レベルの重要なアクション
│  └─ エグゼクティブの認識を得る：デジタル主権のリスク...
└─ 特定された要件：
   └─ データ居住要件
```

## Related Issues

- Depends on: (none - implements i18n from scratch)
- Enables: Future translations (Chinese, Korean, French, German, etc.)
- Improves: Code maintainability through translation key abstraction
- Related: Any existing hardcoded text issues or font rendering issues

## References

- [i18n Design Document](./docs/i18n-design-and-pr-plan.md)
- [IPA Font License](https://opensource.org/licenses/IPA)
- [Dompdf Font Documentation](https://github.com/dompdf/dompdf/wiki/About-Fonts-and-Character-Encoding)
- [PHP-Font-Lib](https://github.com/PhenX/php-font-lib)

---

**I'm happy to adjust the approach based on your feedback!**

Please let me know if you'd like me to:
- Combine or split the PRs differently
- Add more documentation
- Include additional testing
- Address any other concerns

Thank you for considering this contribution! 🙏
