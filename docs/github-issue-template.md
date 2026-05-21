# [PROPOSAL] Add Japanese Language Support with Font Rendering Improvements

## Summary

This proposal adds complete Japanese language support to the Viewfinder assessment tool, building upon the existing i18n infrastructure. The work is split into three focused PRs for easier review.

## Motivation

Digital sovereignty is a critical concern for organizations in Japan and Asia-Pacific. Supporting Japanese language enables:
- 🌏 Broader adoption in Japanese enterprises and government agencies
- 📊 More accurate assessments when conducted in native language
- 🎯 Better engagement with Japanese-speaking stakeholders
- 📄 Professional PDF reports in Japanese

## Current State

The application currently has:
- ✅ Core i18n infrastructure (Phases 1-2 completed)
- ✅ English translations
- ✅ Locale switching mechanism
- ❌ **Missing:** Japanese translations (ja.php)
- ❌ **Missing:** Proper Japanese font rendering in PDFs

## Proposed Changes

### Overview

I propose **three sequential PRs** to upstream these changes:

```
PR #1: Infrastructure (Translation Keys)
   ↓
PR #2: Japanese Translations
   ↓
PR #3: Font Rendering Fixes
```

### PR #1: Add Missing Translation Keys (Refactoring Only)

**What:** Extract hardcoded English strings into translation keys
**Why:** Makes the application properly translatable
**Impact:** Zero visual changes for English users

**Changed Files:**
- `i18n/locales/en.php` (+75 keys)
- `ds-qualifier/generate-pdf.php` (use `__()` function)
- `ds-qualifier/results.php` (use `__()` function)

**Example Change:**
```php
// Before
<h4>Requirements Identified:</h4>

// After
<h4><?php echo __('results.domain_insights.requirements_identified'); ?></h4>
```

**Benefits:**
- Sets up infrastructure for any language
- Easy to review (pure refactoring)
- No breaking changes

---

### PR #2: Add Japanese Translation File

**What:** Add Japanese translations for all keys
**Why:** Enables Japanese locale
**Impact:** Adds Japanese language option, no changes to English

**Changed Files:**
- `i18n/locales/ja.php` (new file, 330+ lines)

**Sample Translations:**
| English | Japanese |
|---------|----------|
| Critical Actions for Initial Level | 初期レベルの重要なアクション |
| Requirements Identified: | 特定された要件： |
| Domain Insights | ドメインインサイト |

**Benefits:**
- Self-contained language file
- Easy to review by native speakers
- Enables full Japanese UI

---

### PR #3: Fix Japanese Font Rendering in PDFs

**What:** Generate proper font metrics for Japanese characters
**Why:** Without this, Japanese PDFs have serious rendering issues
**Impact:** Japanese PDFs render correctly, English unchanged

**The Problem:**

Current Japanese PDF output:
```
❌ Text overflows margins
❌ Characters overlap
❌ Breaks mid-character: "日本|語" instead of "日本語"
❌ Missing glyphs (boxes instead of characters)
```

**The Solution:**

1. **Extract font metrics** from IPA Gothic TrueType font
   - 11,691 character widths mapped to Unicode values
   - Generate Adobe Font Metrics (AFM) file during build

2. **Add locale-specific CSS**
   ```php
   // Japanese: allow breaking anywhere (CJK standard)
   word-break: break-all;

   // English: break at word boundaries
   word-break: normal;
   ```

3. **Optimize layout**
   - Dynamic table layout (`auto` instead of `fixed`)
   - Adjusted font sizes and padding
   - Prevent container overflow

**Changed Files:**
- `generate-font-metrics.php` (new file)
- `ds-qualifier/generate-pdf.php` (locale-specific styling)
- `Dockerfile` (font installation and metrics generation)

**Benefits:**
- ✅ Professional-quality Japanese PDFs
- ✅ Accurate text layout and line breaking
- ✅ No impact on English PDF generation
- ✅ Extensible to other CJK languages

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

### PR #1 Testing
```bash
# English output should be identical to before
diff <(curl http://before/results) <(curl http://after/results)
# Should show: no differences
```

### PR #2 Testing
```bash
# Japanese displays correctly
curl 'http://localhost:8080/?lang=ja' | grep "デジタル主権"

# English still works
curl 'http://localhost:8080/?lang=en' | grep "Digital Sovereignty"
```

### PR #3 Testing
```bash
# Font metrics generated during build
podman run --rm app cat vendor/dompdf/dompdf/lib/fonts/ipaexg.ufm | wc -l
# Expected: 11,712 lines

# Japanese PDF test (manual)
# 1. Generate PDF in Japanese
# 2. Verify no text overflow
# 3. Verify proper line breaks
# 4. Check character spacing
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

1. **PR Sequencing:** Is the 3-PR approach acceptable? Would you prefer different grouping?

2. **Translation Quality:** Should I have the Japanese translations professionally reviewed before PR #2?

3. **Font Licensing:** IPA Gothic font is under [IPA Font License](https://opensource.org/licenses/IPA) (OSI-approved, permissive). Is this acceptable for this project?

4. **Scope:** Are there any additional i18n improvements you'd like included? (e.g., date formatting, number formatting, RTL support framework)

5. **Documentation:** What level of documentation would you like for each PR? (e.g., inline comments, README updates, migration guide)

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

- **Week 1:** PR #1 ready for review
- **Week 2:** PR #2 ready (after PR #1 merge)
- **Week 3:** PR #3 ready (after PR #2 merge)

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

- Depends on: (none - builds on existing i18n infrastructure)
- Blocks: Future translations (Chinese, Korean, etc.)
- Related: Any existing font rendering issues

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
