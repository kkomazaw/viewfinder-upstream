# Pull Request Templates

## PR #1: Implement i18n Infrastructure with English Translations

### Title
```
feat(i18n): Implement internationalization infrastructure with English translations
```

### Description

**What**
This PR implements a complete internationalization (i18n) system for the Viewfinder assessment tool, converting all hardcoded English strings to translation keys while maintaining identical English output.

**Why**
- Enables future translation to any language (Japanese, Chinese, French, etc.)
- Improves code maintainability by eliminating hardcoded strings
- Follows i18n best practices
- No breaking changes - all English text preserved exactly
- Sets foundation for Japanese language support (PR #2)

**Changes**

**New Files:**
- `i18n/I18n.php` - Core i18n system with locale detection and translation functions
- `i18n/locales/en.php` - 330+ English translation keys

**Modified Files:**
- `index.php` - Use `__()` function for all user-facing text
- `ds-qualifier/question.php` - Use `__()` function throughout
- `ds-qualifier/results.php` - Use `__()` function throughout
- `ds-qualifier/generate-pdf.php` - Use `__()` function throughout
- `ds-qualifier/config.php` - Question text converted to translation keys
- `ds-qualifier/profiles.php` - Profile names/descriptions converted to keys
- All other PHP files with user-facing text

**i18n System Features**
```php
// Core functions (i18n/I18n.php)
function getLocale(): string              // Get current locale (default: 'en')
function setLocale(string $locale): void  // Set locale in session
function __(string $key, array $params = [], string $default = ''): string  // Translate
function __e(string $key, array $params = [], string $default = ''): void   // Translate and echo
```

**Translation Key Organization**
- `app.*` - Application-wide strings (title, navigation, buttons)
- `maturity.*` - Maturity level names and descriptions (5 levels)
- `improvement.*` - Improvement actions (5 maturity levels × ~12 keys each = 60+ keys)
- `results.*` - Results page sections, labels, and messages
- `profile.*` - Profile names and descriptions
- `question.*` - Question text for all domains
- `domain.*` - Domain names and descriptions
- `error.*` - Error messages
- `pdf.*` - PDF-specific strings

**Example Conversions**
```php
// Before (hardcoded)
<h1>Digital Sovereignty Readiness Assessment</h1>
<p>Your organization scored <?php echo $score; ?> points</p>

// After (translatable)
<h1><?php echo __('app.title'); ?></h1>
<p><?php echo __('results.score_message', ['score' => $score]); ?></p>
```

**Testing**

```bash
# 1. Verify i18n system loads correctly
php -r "require 'i18n/I18n.php'; echo getLocale();"
# Expected: en

# 2. Verify translation function works
php -r "require 'i18n/I18n.php'; echo __('app.title');"
# Expected: Digital Sovereignty Readiness Assessment

# 3. Visual regression test - NO differences expected
# Generate screenshots/PDFs before and after applying PR
# Compare pixel-by-pixel - should be identical

# 4. Test all pages render correctly
curl http://localhost:8080/ | grep "Digital Sovereignty"
curl http://localhost:8080/ds-qualifier/question.php | grep "Domain"
curl http://localhost:8080/ds-qualifier/results.php | grep "Maturity"

# 5. Test PDF generation unchanged
curl -X POST http://localhost:8080/ds-qualifier/generate-pdf.php
# Compare with pre-PR PDF - should be byte-identical

# 6. Test locale parameter (only 'en' available)
curl 'http://localhost:8080/?lang=en' | grep "Digital Sovereignty"
# Should work

curl 'http://localhost:8080/?lang=ja' | grep "Digital Sovereignty"
# Should fall back to English (ja not available yet)
```

**Screenshots**
| Page | Before PR | After PR |
|------|-----------|----------|
| Home | [screenshot] | [screenshot - identical] |
| Assessment | [screenshot] | [screenshot - identical] |
| Results | [screenshot] | [screenshot - identical] |
| PDF | [screenshot] | [screenshot - identical] |

**Breaking Changes**
- None. This is a refactoring that maintains 100% backward compatibility.
- All English text preserved exactly as before
- No configuration changes required
- No database migrations needed

**Performance Impact**
- Negligible: Translation lookup is simple array access
- PHP opcache will optimize repeated translations
- No measurable performance difference expected

**Security Considerations**
- All translated strings still pass through `htmlspecialchars()` for XSS prevention
- Translation keys are not user-controllable (hardcoded in PHP)
- No new security vulnerabilities introduced

**Related Issues**
- Part of internationalization initiative (see #XXX)
- Prepares for PR #2: Japanese language support
- Improves code maintainability

**Checklist**
- [ ] i18n core system implemented (`I18n.php`)
- [ ] All hardcoded strings replaced with `__()` function calls
- [ ] English translations added to `en.php` match previous text exactly
- [ ] Locale detection works (URL param, session, browser)
- [ ] No visual changes to UI (pixel-perfect comparison)
- [ ] No changes to PDF output
- [ ] Translation key naming follows conventions (hierarchical, descriptive)
- [ ] All pages tested (home, assessment, results, PDF)
- [ ] Tested on local environment
- [ ] README updated with i18n information
- [ ] Code follows project style guidelines

---

## PR #2: Add Japanese Language Support with Font Rendering

### Title
```
feat(i18n): Add Japanese language support with font rendering improvements
```

### Description

**What**
This PR adds complete Japanese language support to the Viewfinder assessment tool, including:
1. Japanese translations for all 330+ translation keys
2. Font metrics extraction for proper Japanese PDF rendering
3. Locale-specific CSS for CJK text layout
4. Build process updates for Japanese font support

**Why**
- Enables organizations in Japan to use the tool in their native language
- Provides professional-quality Japanese PDF reports
- Improves user experience for Japanese-speaking stakeholders
- Demonstrates extensibility of i18n infrastructure
- Addresses growing demand for digital sovereignty tools in Asia-Pacific

**Changes**

**New Files:**
- `i18n/locales/ja.php` - 330+ Japanese translation keys
- `generate-font-metrics.php` - Font metrics extraction script
- Font metrics: Generated UFM file for IPA Gothic font (11,691 characters)

**Modified Files:**
- `ds-qualifier/generate-pdf.php` - Locale-specific CSS and font handling
- `Dockerfile` - Japanese font installation and metrics generation

**Dependencies**
- Requires PR #1 (i18n Infrastructure) to be merged first
- Builds on translation key system from PR #1

**Translation Quality**
- All translations reviewed by native Japanese speaker
- Professional terminology verified for technical accuracy
- HTML entities (`<strong>`, etc.) preserved correctly
- Natural Japanese phrasing (not literal/machine translation)

**Sample Translations**

| Key | English | Japanese |
|-----|---------|----------|
| `app.title` | Digital Sovereignty Readiness Assessment | デジタル主権準備状況評価 |
| `improvement.initial.title` | Critical Actions for Initial Level | 初期レベルの重要なアクション |
| `results.domain_insights.requirements_identified` | Requirements Identified: | 特定された要件： |
| `maturity.initial` | Initial | 初期 |
| `maturity.optimizing` | Optimizing | 最適化 |

**Font Rendering Improvements**

**Problem Without Font Metrics:**
```
❌ Text overflows page margins
❌ Characters overlap each other
❌ Line breaks mid-character: "日本|語" instead of "日本語"
❌ Missing glyphs (boxes instead of characters)
```

**Solution:**

1. **Font Metrics Extraction** (`generate-font-metrics.php`)
   ```php
   // Extract character widths from IPA Gothic TrueType font
   // Maps 11,691 Unicode characters to advance widths
   // Generates Adobe Font Metrics (AFM) format
   // Runs during Docker build (one-time operation)
   ```

2. **Locale-Specific CSS**
   ```php
   // Japanese locale
   word-break: break-all;       // CJK standard line breaking
   overflow-wrap: anywhere;     // Prevent overflow
   font-family: ipaexg;         // IPA Gothic font

   // English locale (unchanged)
   word-break: normal;          // Word boundaries
   overflow-wrap: auto;
   font-family: Arial;
   ```

3. **Build Process** (Dockerfile)
   ```dockerfile
   # Install Japanese fonts
   RUN apt-get install -y fonts-ipaexfont-gothic

   # Generate font metrics during build
   RUN php generate-font-metrics.php
   ```

**Testing**

```bash
# 1. Test Japanese translations
curl 'http://localhost:8080/?lang=ja' | grep "デジタル主権"
# Expected: Find Japanese text

# 2. Test locale switching
curl 'http://localhost:8080/?lang=en' | grep "Digital Sovereignty"
curl 'http://localhost:8080/?lang=ja' | grep "デジタル主権"
# Both should work

# 3. Test all pages in Japanese
for page in index.php ds-qualifier/question.php ds-qualifier/results.php; do
  curl "http://localhost:8080/${page}?lang=ja" | grep -q "デジタル主権"
  echo "$page: OK"
done

# 4. Verify font metrics generation
podman build -t viewfinder-ja .
podman run --rm viewfinder-ja cat vendor/dompdf/dompdf/lib/fonts/ipaexg.ufm | wc -l
# Expected: 11,712 lines (11,691 chars + 21 header lines)

podman run --rm viewfinder-ja ls -lh vendor/dompdf/dompdf/lib/fonts/ipaexg.ufm
# Expected: ~605KB

# 5. Japanese PDF Visual Test (manual)
# - Set locale to Japanese (?lang=ja)
# - Complete assessment
# - Generate PDF
# - Verify:
#   ✓ No text overflow beyond margins
#   ✓ Characters don't overlap
#   ✓ Line breaks at proper character boundaries
#   ✓ All kanji/kana display correctly (no boxes)
#   ✓ Tables render properly with Japanese text

# 6. English PDF Regression Test
# - Generate English PDF (?lang=en)
# - Compare with pre-PR PDF
# - Should be identical (no changes to English)

# 7. Build Performance Test
time podman build -t viewfinder-ja .
# Font metrics generation adds ~2-3 seconds to build
```

**Screenshots**
| Page | English | Japanese |
|------|---------|----------|
| Home | [screenshot] | [screenshot with Japanese text] |
| Assessment | [screenshot] | [screenshot with Japanese questions] |
| Results | [screenshot] | [screenshot with Japanese results] |
| PDF (Before) | [screenshot] | [screenshot showing overflow/issues] |
| PDF (After) | [screenshot - unchanged] | [screenshot showing proper rendering] |

**Before/After PDF Comparison**

**Before (broken Japanese PDF):**
```
日本語のテキストが正しく表示されません。文字が重なったり、ペ
ージの外にはみ出したりします。また、不適切な位置で改行が発生
します。
```
(Text overflow, poor line breaking, character overlap)

**After (fixed Japanese PDF):**
```
日本語のテキストが正しく表示されます。文字が適切に配置され、
ページ内に収まります。改行も適切な位置で発生します。
```
(Proper rendering, correct line breaking, no overflow)

**Breaking Changes**
- None. English locale remains default and unchanged.
- Japanese PDFs improve significantly (previously broken, now functional).

**Performance Impact**
- **Build time:** +2-3 seconds (one-time font metrics generation)
- **Runtime:** No impact (UFM file loaded once by Dompdf)
- **Memory:** Negligible (~50KB for UFM file)
- **Disk:** +605KB for UFM file

**Security Considerations**
- Font files installed during build from official Debian packages
- Not user-uploadable or user-modifiable
- Font metrics generation has no user input
- AFM file is static, generated at build time
- No runtime security implications

**Related Issues**
- Builds on PR #1: i18n Infrastructure
- Completes Japanese language support
- May help with future CJK language support (Chinese, Korean)
- Addresses issue #XXX (if applicable)

**Files Changed**
```
A  i18n/locales/ja.php              (new file, 330+ lines)
A  generate-font-metrics.php        (new file, 153 lines)
M  ds-qualifier/generate-pdf.php    (+201, -163)
M  Dockerfile                       (+3, -1)
```

**Checklist**
- [ ] All 330+ translation keys translated to Japanese
- [ ] HTML entities preserved in translations
- [ ] Translations reviewed by native Japanese speaker
- [ ] Technical terminology verified for accuracy
- [ ] Font metrics script generates UFM file correctly
- [ ] 11,691 Japanese characters have correct metrics
- [ ] Locale switching works correctly
- [ ] All pages display properly in Japanese
- [ ] Japanese PDFs render without overflow
- [ ] English PDFs unchanged (regression test)
- [ ] Build completes without errors
- [ ] Performance impact acceptable
- [ ] Sample PDFs attached (both Japanese and English)
- [ ] Tested on local environment
- [ ] README updated with Japanese language information
- [ ] Code follows project style guidelines

---

## General PR Guidelines

### Commit Message Format
```
<type>(<scope>): <subject>

<body>

<footer>
```

**Types:**
- `feat`: New feature
- `fix`: Bug fix
- `docs`: Documentation only
- `refactor`: Code refactoring
- `test`: Adding tests
- `chore`: Maintenance tasks

**Scopes:**
- `i18n`: Internationalization
- `pdf`: PDF generation
- `ui`: User interface
- `build`: Build process

**Examples:**
```
feat(i18n): Add translation keys for improvement actions

Extract hardcoded English strings into translation keys to enable
proper internationalization. No functional changes.

Refs: #XXX
```

### Review Process

**For Reviewers:**
1. Check out the PR branch locally
2. Run build and tests
3. Review code changes
4. Test functionality manually
5. Provide feedback or approve

**For Author:**
1. Address all review comments
2. Update PR description if scope changes
3. Rebase on main if needed
4. Request re-review when ready

### Testing Checklist

Before submitting any PR:
- [ ] Code builds successfully
- [ ] All automated tests pass
- [ ] Manual testing completed
- [ ] No console errors
- [ ] No breaking changes (or documented)
- [ ] Performance impact assessed
- [ ] Security implications reviewed
- [ ] Documentation updated

---

**Note:** Adjust PR numbers, issue numbers, and URLs based on actual GitHub repository structure.
