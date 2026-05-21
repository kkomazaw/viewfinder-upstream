# Internationalization (i18n) Design and Pull Request Plan

## Executive Summary

This document outlines the internationalization design and proposes a phased approach to upstreaming Japanese language support and related improvements. The work builds upon the existing i18n infrastructure (Phases 1-2) and adds Japanese translation support with critical font rendering fixes.

## Background

The Viewfinder assessment tool currently has a core i18n infrastructure supporting English. This proposal extends support to Japanese (ja) locale, including:

1. Complete Japanese translations (75+ new translation keys)
2. Japanese font rendering fixes for PDF generation
3. Locale-specific CSS and layout improvements

## Design Principles

### 1. Translation Key Organization

Translation keys follow a hierarchical naming convention:

```
{section}.{subsection}.{specific_item}
```

**Examples:**
- `improvement.initial.title` - Improvement action titles
- `improvement.initial.action1` - Specific action items
- `results.domain_insights.intro` - Section introductions

### 2. Locale-Specific Behavior

The system adapts to locale differences:

**Font Selection:**
- `en`: Arial (sans-serif)
- `ja`: IPA Ex Gothic (Japanese font with proper character support)

**Text Wrapping:**
- `en`: `word-break: normal` (standard English word boundaries)
- `ja`: `word-break: break-all` (CJK text can break anywhere to prevent overflow)

**PDF Layout:**
- Dynamic font sizing based on locale
- Locale-specific character metrics (UFM files)

### 3. Font Metrics System

For proper Japanese text rendering in PDFs, we extract character metrics from TrueType fonts:

```
TrueType Font → FontLib Parser → Unicode Character Map → AFM Format → Dompdf
```

**Key Features:**
- Extracts 11,691 character widths from IPA Gothic font
- Maps Unicode code points to character widths
- Generates Adobe Font Metrics (AFM) format files
- Enables accurate text layout and line breaking

## Architecture Diagram

```
┌─────────────────────────────────────────────────────────────┐
│                    User Request                              │
│                         │                                    │
│                         ↓                                    │
│              ┌──────────────────────┐                       │
│              │   I18n System        │                       │
│              │   (I18n.php)         │                       │
│              └──────────┬───────────┘                       │
│                         │                                    │
│         ┌───────────────┴────────────────┐                 │
│         ↓                                 ↓                 │
│  ┌─────────────┐                  ┌─────────────┐          │
│  │  en.php     │                  │  ja.php     │          │
│  │  (English)  │                  │  (Japanese) │          │
│  └─────────────┘                  └─────────────┘          │
│                                                              │
│                    Application Layer                        │
│         ┌──────────────┬──────────────┬─────────────┐      │
│         ↓              ↓              ↓             ↓      │
│   ┌─────────┐   ┌──────────┐   ┌─────────┐   ┌─────────┐ │
│   │ index   │   │ question │   │ results │   │   PDF   │ │
│   │  .php   │   │  .php    │   │  .php   │   │  gen    │ │
│   └─────────┘   └──────────┘   └─────────┘   └─────────┘ │
│                                                      │       │
│                                                      ↓       │
│                                            ┌──────────────┐ │
│                                            │   Dompdf     │ │
│                                            │  + UFM fonts │ │
│                                            └──────────────┘ │
└─────────────────────────────────────────────────────────────┘
```

## Proposed Pull Request Plan

To facilitate upstream review, we propose splitting the work into **three focused PRs**:

### PR #1: Add Missing Translation Keys (Infrastructure)

**Scope:** Add translation keys for improvement actions and domain insights

**Files Changed:**
- `i18n/locales/en.php` (75 new translation keys)
- `ds-qualifier/generate-pdf.php` (use translation keys instead of hardcoded strings)
- `ds-qualifier/results.php` (use translation keys instead of hardcoded strings)

**Benefits:**
- Makes existing English text translatable
- No functional changes for English users
- Establishes infrastructure for any future translations
- Easy to review (just refactoring)

**Translation Keys Added:**
```php
// Improvement Actions (5 maturity levels × ~12 keys each)
'improvement.initial.title'
'improvement.initial.intro'
'improvement.initial.action1' through 'action6'
'improvement.initial.priorities'
'improvement.initial.priority1' through 'priority4'
// ... similar for managed, defined, quantitative, optimizing

// Domain Insights
'results.domain_insights.intro'
'results.domain_insights.requirements_identified'
'results.domain_insights.no_requirements'
```

**Testing:**
- ✅ All English text displays identically to before
- ✅ No visual changes in UI
- ✅ PDF generation unchanged

---

### PR #2: Add Japanese Language Support

**Scope:** Add Japanese translation file

**Files Changed:**
- `i18n/locales/ja.php` (new file, 75+ translation keys)

**Benefits:**
- Self-contained language file
- Builds on PR #1 infrastructure
- Easy to review (translations only)
- Enables Japanese locale selection

**Sample Translations:**
```php
// Initial Level (初期レベル)
'improvement.initial.title' => '初期レベルの重要なアクション',
'improvement.initial.intro' => 'プロセスは予測不可能で対応的です。基本的なデジタル主権の認識と管理を確立します：',
'improvement.initial.action1' => '<strong>エグゼクティブの認識を得る：</strong> デジタル主権のリスクと規制要件について経営陣を教育',
// ... (72 more keys)
```

**Testing:**
- ✅ Japanese locale displays all text in Japanese
- ✅ English locale unaffected
- ✅ All UI elements properly translated

---

### PR #3: Fix Japanese Font Rendering in PDFs

**Scope:** Enable proper Japanese character rendering in PDF generation

**Files Changed:**
- `generate-font-metrics.php` (new file)
- `ds-qualifier/generate-pdf.php` (locale-specific CSS and layout)
- `Dockerfile` (font installation and metrics generation)

**Problem Statement:**

Without proper font metrics, Japanese text in PDFs experiences:
- ❌ Text overflow beyond margins
- ❌ Incorrect line breaking (breaks in middle of characters)
- ❌ Character overlap
- ❌ Missing characters (replaced with boxes)

**Solution:**

1. **Font Metrics Extraction**
   - Extract character widths from IPA Gothic TrueType font
   - Generate Adobe Font Metrics (AFM) file with 11,691 character widths
   - Map Unicode code points to advance widths for accurate layout

2. **Locale-Specific CSS**
   ```php
   if ($locale === 'ja') {
       $wordBreakStyle = 'break-all';      // Allow breaking anywhere
       $lineBreakStyle = 'anywhere';       // CJK line breaking
   } else {
       $wordBreakStyle = 'normal';         // English word boundaries
       $lineBreakStyle = 'auto';           // Standard wrapping
   }
   ```

3. **Table Layout**
   - Changed from `table-layout: fixed` to `auto`
   - Allows flexible column widths based on content
   - Prevents Japanese text overflow in table cells

4. **Font Sizing**
   - Reduced font sizes for better fit (e.g., titles: 24px → 20px)
   - Added `box-sizing: border-box` to prevent overflow
   - Increased padding for readability

**Technical Details:**

The font metrics extraction process:

```php
// 1. Load TrueType font
$font = Font::load('ipaexg.ttf');
$font->parse();

// 2. Get Unicode character map
$charMap = $font->getUnicodeCharMap();  // 11,691 characters

// 3. Extract horizontal metrics for each character
foreach ($charMap as $unicode => $gid) {
    $metrics = $hmtx[$gid];  // [advanceWidth, leftSideBearing]
    $advanceWidth = is_array($metrics) ? $metrics[0] : $metrics;
    $scaledWidth = round(($advanceWidth / $unitsPerEm) * 1000);
    $glyphWidths[$unicode] = $scaledWidth;
}

// 4. Generate AFM file
$afm .= "C {$unicode} ; WX {$width} ; N uni" . dechex($unicode) . " ; B 0 -200 {$width} 800 ;\n";
```

**Benefits:**
- ✅ Proper Japanese text wrapping in PDFs
- ✅ No character overflow or overlap
- ✅ Accurate character spacing
- ✅ Maintains English PDF quality

**Testing:**
- ✅ Japanese PDFs render correctly with proper line breaks
- ✅ No text overflow in any section
- ✅ English PDFs unaffected
- ✅ All 11,691 Japanese characters have correct metrics

---

## Review Checkpoints

Each PR includes specific review checkpoints:

### PR #1 Review Checklist
- [ ] All hardcoded English strings replaced with translation keys
- [ ] English translations match previous hardcoded text exactly
- [ ] No functional changes to English UI
- [ ] Translation key naming follows established conventions
- [ ] PDF generation produces identical output for English

### PR #2 Review Checklist
- [ ] Japanese translations are accurate and natural
- [ ] All translation keys from PR #1 are translated
- [ ] Locale switching works correctly
- [ ] UI displays properly with Japanese text (no layout breaks)
- [ ] HTML entities (e.g., `<strong>`) render correctly

### PR #3 Review Checklist
- [ ] Font metrics file generates successfully during build
- [ ] Japanese PDFs display without text overflow
- [ ] Character widths are accurate (spot check common characters)
- [ ] English PDF generation unaffected
- [ ] Build process completes without errors
- [ ] Font files have correct permissions

## Testing Strategy

### Manual Testing
1. **English Locale:**
   - View all pages (index, assessment, results)
   - Generate PDF report
   - Verify no visual changes from previous version

2. **Japanese Locale:**
   - Switch to Japanese locale
   - Complete full assessment
   - Verify all text displays in Japanese
   - Generate PDF and verify proper rendering
   - Check for text overflow issues

### Automated Testing
```bash
# Build test
podman build -t viewfinder-test .

# Font metrics verification
podman run --rm viewfinder-test cat vendor/dompdf/dompdf/lib/fonts/ipaexg.ufm | wc -l
# Expected: 11,712 lines (11,691 chars + headers)

# Locale switching test
curl http://localhost:8080/?lang=ja | grep "デジタル主権"
curl http://localhost:8080/?lang=en | grep "Digital Sovereignty"
```

## Migration Path

For existing deployments:

1. **After PR #1:** No action required. English text continues to work.
2. **After PR #2:** Japanese locale becomes available. No breaking changes.
3. **After PR #3:** Rebuild container to generate font metrics. One-time operation.

## Performance Considerations

**Font Metrics Generation:**
- Runs once during Docker build
- Takes ~2-3 seconds
- Generates 605KB UFM file
- No runtime performance impact

**Translation Loading:**
- PHP arrays loaded once per request
- Minimal memory overhead (~50KB per locale)
- No caching required (PHP opcache handles it)

## Future Extensibility

This design supports additional locales easily:

```bash
# Add new locale (e.g., German)
cp i18n/locales/en.php i18n/locales/de.php
# Translate all keys to German
# Done! No code changes needed.
```

For languages requiring special fonts (Korean, Chinese, etc.):
1. Add font files to Dockerfile
2. Update font metrics generation script
3. Add locale-specific CSS rules
4. Create translation file

## Security Considerations

- ✅ All user-facing text uses `htmlspecialchars()` for XSS prevention
- ✅ Translation files are PHP arrays (no eval/include vulnerabilities)
- ✅ Font files installed during build (not user-uploadable)
- ✅ No user input in translation key selection

## Backward Compatibility

All changes are backward compatible:

- ✅ Existing English installations continue to work
- ✅ Default locale remains English
- ✅ No database migrations required
- ✅ No configuration changes needed

## Questions for Reviewers

1. **Translation Key Naming:** Do the key names follow your preferred conventions?
2. **PR Sequencing:** Is the proposed 3-PR approach acceptable, or would you prefer different grouping?
3. **Font License:** IPA Gothic is under IPA Font License (permissive). Is this acceptable?
4. **Locale Detection:** Should we add browser language auto-detection, or keep manual selection?
5. **RTL Support:** Should we design with RTL (Arabic, Hebrew) in mind for future additions?

## Timeline Estimate

- **PR #1:** ~1-2 weeks (refactoring review)
- **PR #2:** ~1 week (translation review, may need native speaker)
- **PR #3:** ~2 weeks (technical review, testing on different platforms)

**Total:** 4-5 weeks for all three PRs

## References

- [IPA Font License](https://opensource.org/licenses/IPA)
- [Dompdf Documentation](https://github.com/dompdf/dompdf)
- [PHP-Font-Lib](https://github.com/PhenX/php-font-lib)
- [Adobe Font Metrics Specification](https://adobe-type-tools.github.io/font-tech-notes/pdfs/5004.AFM_Spec.pdf)

## Conclusion

This phased approach allows reviewers to:
1. Evaluate infrastructure changes separately from translations
2. Review translations independently from technical font rendering
3. Test each component in isolation
4. Provide focused feedback on each aspect

We believe this design balances maintainability, performance, and extensibility while keeping changes reviewable and testable.

---

**Author:** Kenichiro Komazawa
**Date:** 2026-05-21
**Version:** 1.0
