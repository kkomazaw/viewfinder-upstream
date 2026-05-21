# Pull Request Templates

## PR #1: Add Missing Translation Keys for i18n Support

### Title
```
feat(i18n): Add translation keys for improvement actions and domain insights
```

### Description

**What**
This PR extracts hardcoded English strings into translation keys, making the application properly translatable without changing any English text or behavior.

**Why**
- Enables future translation to any language
- Follows i18n best practices
- Makes codebase more maintainable
- Prepares for Japanese language support (PR #2)

**Changes**
- Added 75 new translation keys to `i18n/locales/en.php`
- Updated `ds-qualifier/generate-pdf.php` to use `__()` function
- Updated `ds-qualifier/results.php` to use `__()` function
- All English text remains identical to previous version

**Translation Keys Added**
- `improvement.*` - 72 keys for improvement actions (5 maturity levels)
- `results.domain_insights.*` - 3 keys for domain insights section

**Testing**
```bash
# Visual diff test - should show NO differences
curl http://localhost:8080/ds-qualifier/results.php > before.html
# Apply PR changes
curl http://localhost:8080/ds-qualifier/results.php > after.html
diff before.html after.html
# Expected: No differences in rendered HTML

# PDF generation test
# Generate PDF before and after - should be identical
```

**Screenshots**
- Before: [screenshot of English results page]
- After: [screenshot of English results page - should be identical]

**Breaking Changes**
- None. This is pure refactoring.

**Related Issues**
- Part of Japanese language support initiative (see #XXX)
- Prepares for PR #2: Japanese translations

**Checklist**
- [ ] All hardcoded strings replaced with `__()` function calls
- [ ] English translations added to `en.php` match previous text exactly
- [ ] No visual changes to UI
- [ ] No changes to PDF output
- [ ] Translation key naming follows existing conventions
- [ ] Tested on local environment
- [ ] Documentation updated (if needed)

---

## PR #2: Add Japanese Language Support (ja.php)

### Title
```
feat(i18n): Add Japanese translation file (ja.php)
```

### Description

**What**
This PR adds complete Japanese translations for the Viewfinder assessment tool, enabling Japanese language support.

**Why**
- Enables organizations in Japan to use the tool in their native language
- Improves user experience for Japanese-speaking stakeholders
- Demonstrates extensibility of i18n infrastructure
- Addresses growing demand for digital sovereignty tools in Asia-Pacific

**Changes**
- Added `i18n/locales/ja.php` with 330+ lines of Japanese translations
- All 75 translation keys from PR #1 are translated
- Includes translations for all maturity levels, improvement actions, and UI elements

**Dependencies**
- Requires PR #1 to be merged first
- Font rendering improvements (PR #3) recommended but not required

**Translation Quality**
- All translations reviewed by native Japanese speaker
- Professional terminology verified for technical accuracy
- HTML entities (`<strong>`, etc.) preserved correctly

**Testing**
```bash
# Test Japanese locale
curl 'http://localhost:8080/?lang=ja' > ja_output.html
grep "デジタル主権" ja_output.html  # Should find Japanese text

# Test locale switching
curl 'http://localhost:8080/?lang=en' > en_output.html
grep "Digital Sovereignty" en_output.html  # Should find English text

# Test all pages in Japanese
for page in index.php ds-qualifier/question.php ds-qualifier/results.php; do
  curl "http://localhost:8080/${page}?lang=ja" | grep -q "デジタル主権"
done
```

**Screenshots**
| Page | English | Japanese |
|------|---------|----------|
| Home | [screenshot] | [screenshot] |
| Assessment | [screenshot] | [screenshot] |
| Results | [screenshot] | [screenshot] |
| PDF | [screenshot] | [screenshot] |

**Sample Translations**

| Key | English | Japanese |
|-----|---------|----------|
| `improvement.initial.title` | Critical Actions for Initial Level | 初期レベルの重要なアクション |
| `results.domain_insights.requirements_identified` | Requirements Identified: | 特定された要件： |
| `maturity.initial` | Initial | 初期 |

**Known Issues**
- Japanese text in PDFs may have minor layout issues (addressed in PR #3)
- Some line breaks may not be optimal for Japanese (addressed in PR #3)

**Breaking Changes**
- None. English locale remains default and unchanged.

**Related Issues**
- Builds on PR #1: Translation keys
- Prepares for PR #3: Font rendering improvements
- Addresses issue #XXX (if applicable)

**Checklist**
- [ ] All translation keys from PR #1 are translated
- [ ] HTML entities preserved in translations
- [ ] Translations reviewed by native speaker
- [ ] Technical terminology verified for accuracy
- [ ] Locale switching works correctly
- [ ] All pages display properly in Japanese
- [ ] No layout breaking with Japanese text
- [ ] Tested on local environment
- [ ] README updated with language information

---

## PR #3: Fix Japanese Font Rendering in PDFs

### Title
```
fix(pdf): Add font metrics extraction for proper Japanese text rendering
```

### Description

**What**
This PR fixes critical Japanese font rendering issues in PDF generation by extracting proper font metrics and implementing locale-specific layout improvements.

**Why**
Without this fix, Japanese PDFs have serious problems:
- ❌ Text overflows page margins
- ❌ Characters overlap each other
- ❌ Line breaks occur mid-character
- ❌ Missing glyphs (boxes instead of characters)

This PR enables professional-quality Japanese PDF reports.

**Changes**

**1. Font Metrics Extraction**
- Added `generate-font-metrics.php` script
- Extracts 11,691 character widths from IPA Gothic TrueType font
- Generates Adobe Font Metrics (AFM) file at build time
- Maps Unicode code points to advance widths for accurate layout

**2. Locale-Specific CSS**
- Japanese: `word-break: break-all` (CJK standard line breaking)
- Japanese: `overflow-wrap: anywhere` (prevent overflow)
- English: maintains existing behavior

**3. PDF Layout Improvements**
- Changed table layout from `fixed` to `auto` for flexible columns
- Reduced font sizes for better fit (e.g., title: 24px → 20px)
- Added `box-sizing: border-box` to prevent overflow
- Increased padding for Japanese readability

**4. Build Process**
- Modified Dockerfile to run font metrics generation during build
- One-time operation, no runtime performance impact

**Technical Details**

Font metrics extraction process:
```php
// 1. Load TrueType font (IPA Gothic, 2048 units/em)
$font = Font::load('ipaexg.ttf');

// 2. Get Unicode character map (11,691 characters)
$charMap = $font->getUnicodeCharMap();

// 3. Extract advance width for each character
foreach ($charMap as $unicode => $gid) {
    $advanceWidth = $hmtx[$gid][0];
    $scaledWidth = round(($advanceWidth / 2048) * 1000);
    $glyphWidths[$unicode] = $scaledWidth;
}

// 4. Generate AFM file (605KB)
// Format: C {unicode} ; WX {width} ; N uni{hex} ; B 0 -200 {width} 800 ;
```

**Dependencies**
- Requires PR #1 and PR #2 for full Japanese support
- Works standalone but benefits from Japanese translations

**Testing**

**Build Test:**
```bash
# Verify font metrics generation
podman build -t viewfinder-test .
podman run --rm viewfinder-test cat vendor/dompdf/dompdf/lib/fonts/ipaexg.ufm | wc -l
# Expected: 11,712 lines (11,691 chars + 21 header lines)

# Verify file size
podman run --rm viewfinder-test ls -lh vendor/dompdf/dompdf/lib/fonts/ipaexg.ufm
# Expected: ~605K
```

**Visual Test:**
```bash
# 1. Set locale to Japanese
# 2. Complete assessment
# 3. Generate PDF
# 4. Verify:
#    - No text overflow beyond margins
#    - Characters don't overlap
#    - Line breaks occur at proper boundaries
#    - All characters display correctly (no boxes)
```

**Regression Test:**
```bash
# Generate English PDF - should be unchanged
# Compare before/after PDF files
```

**Performance Test:**
```bash
# Font metrics generation (during build)
time php generate-font-metrics.php
# Expected: ~2-3 seconds

# PDF generation (runtime - should be unaffected)
time curl -X POST http://localhost:8080/ds-qualifier/generate-pdf.php
# Expected: No significant change from before
```

**Before/After Comparison**

**Before:**
```
日本語のテキストが正しく表示されません。文字が重なったり、ペ
ージの外にはみ出したりします。また、不適切な位置で改行が発生
します。
```
(Text overflow, poor line breaking, character overlap)

**After:**
```
日本語のテキストが正しく表示されます。文字が適切に配置され、
ページ内に収まります。改行も適切な位置で発生します。
```
(Proper rendering, correct line breaking, no overflow)

**Screenshots**
| Section | Before (Broken) | After (Fixed) |
|---------|----------------|---------------|
| Score Card | [screenshot showing overflow] | [screenshot showing proper layout] |
| Improvement Actions | [screenshot showing character overlap] | [screenshot showing correct spacing] |
| Domain Insights | [screenshot showing poor line breaks] | [screenshot showing proper breaks] |

**Breaking Changes**
- None. English PDF generation unchanged.
- Japanese PDFs improve significantly.

**Performance Impact**
- Build time: +2-3 seconds (one-time, during Docker build)
- Runtime: No impact
- UFM file size: 605KB (loaded once by Dompdf)
- Memory: Negligible (~50KB additional)

**Security Considerations**
- Font files installed during build (not user-uploadable)
- No user input in font metrics generation
- AFM file is static, generated at build time
- No runtime security implications

**Related Issues**
- Completes Japanese language support (PR #1, PR #2)
- Fixes issue #XXX: Japanese PDF rendering
- May help with future CJK language support (Chinese, Korean)

**Files Changed**
```
A  generate-font-metrics.php          (new file, 153 lines)
M  ds-qualifier/generate-pdf.php      (+201, -163)
M  Dockerfile                         (+3, -1)
```

**Checklist**
- [ ] Font metrics file generates successfully
- [ ] 11,691 characters extracted correctly
- [ ] Japanese PDFs render without overflow
- [ ] English PDFs unchanged
- [ ] Build completes without errors
- [ ] All tests pass
- [ ] Performance impact acceptable
- [ ] Documentation updated
- [ ] Sample PDFs attached (Japanese and English)

**Migration Guide**

For existing deployments:
```bash
# 1. Pull latest code
git pull origin main

# 2. Rebuild container (generates font metrics)
podman build -t viewfinder:latest .

# 3. Deploy
podman run -d -p 8080:8080 viewfinder:latest

# No configuration changes needed
# No data migration required
```

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
