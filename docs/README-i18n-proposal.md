# Internationalization Proposal Documentation

This directory contains the complete documentation for proposing internationalization (i18n) infrastructure and Japanese language support to upstream maintainers.

## 📄 Documents

### 1. [i18n-design-and-pr-plan.md](./i18n-design-and-pr-plan.md)
**Complete Technical Design Document**

Use this for:
- Understanding the full technical design
- Reference during PR review
- Answering detailed technical questions
- Future contributors looking to add more languages

Contents:
- Architecture overview with diagrams
- Design principles and conventions
- Font metrics system explanation
- Performance analysis
- Security considerations
- Testing strategy
- Future extensibility plan

**Audience:** Technical reviewers, future contributors

---

### 2. [github-issue-template.md](./github-issue-template.md)
**GitHub Issue Proposal**

Use this to:
- Create an issue proposing the changes
- Get feedback before investing in PR work
- Explain the value proposition to maintainers
- Start a discussion on the approach

**How to use:**
1. Copy the contents
2. Create a new issue on GitHub
3. Paste and adjust as needed
4. Tag relevant maintainers

**What it includes:**
- High-level summary
- Motivation and benefits
- Overview of 2-stage PR plan
- Questions for maintainers
- Sample screenshots
- Timeline estimate

**Audience:** Project maintainers, stakeholders

---

### 3. [pr-templates.md](./pr-templates.md)
**Pull Request Templates**

Use this when:
- Creating actual PRs
- Need standardized PR format
- Want comprehensive checklists

**Contents:**
- Template for PR #1 (i18n Infrastructure + English)
- Template for PR #2 (Japanese Language + Font Rendering)
- Commit message format guidelines
- Review process guidelines
- Testing checklists

**How to use:**
1. When ready to create a PR, copy the relevant template
2. Fill in the blanks (screenshots, issue numbers, etc.)
3. Use the checklist to ensure completeness
4. Submit PR with comprehensive description

**Audience:** PR authors, code reviewers

---

## 🚀 Recommended Workflow

### Step 1: Proposal Phase (Week 0)

1. **Create GitHub Issue**
   ```bash
   # Copy content from github-issue-template.md
   # Create issue on GitHub
   # Title: "[PROPOSAL] Add Internationalization Support and Japanese Language"
   ```

2. **Get Feedback**
   - Tag maintainers for feedback
   - Answer questions about i18n system design
   - Discuss PR sequencing (2-PR approach)
   - Adjust approach based on comments
   - Get approval to proceed with PRs

3. **Expected Outcome**
   - ✅ Maintainers approve 2-stage approach
   - ✅ i18n system design approved
   - ✅ Any concerns addressed
   - ✅ Green light to start PR #1

---

### Step 2: PR #1 - i18n Infrastructure + English (Week 1-3)

1. **Create Feature Branch**
   ```bash
   git checkout -b feat/i18n-infrastructure
   ```

2. **Prepare Changes**
   ```bash
   # Add i18n core system
   git add i18n/I18n.php
   git add i18n/locales/en.php

   # Update all PHP files to use __() function
   git add index.php
   git add ds-qualifier/question.php
   git add ds-qualifier/results.php
   git add ds-qualifier/generate-pdf.php
   # ... and all other files with hardcoded text

   git commit -m "feat(i18n): Implement internationalization infrastructure with English translations"
   ```

3. **Create PR**
   - Use template from `pr-templates.md` (PR #1 section)
   - Include before/after screenshots (should be identical)
   - Demonstrate locale detection working
   - Add comprehensive test results
   - Reference design doc for technical details

4. **Expected Timeline**
   - Create PR: Day 1
   - Review cycles: 1-2 weeks (larger PR, architectural changes)
   - Address feedback and iterate
   - Merge: End of Week 2-3

---

### Step 3: PR #2 - Japanese Language + Font Rendering (Week 4-6)

1. **Wait for PR #1 Merge**
   - Don't start until PR #1 is merged and deployed
   - Verify i18n infrastructure works in production
   - Base branch on merged PR #1

2. **Create Feature Branch**
   ```bash
   git checkout main
   git pull
   git checkout -b feat/japanese-language-support
   ```

3. **Prepare All Japanese Changes**
   ```bash
   # Add Japanese translations
   git add i18n/locales/ja.php

   # Add font metrics extraction script
   git add generate-font-metrics.php

   # Update PDF generation for locale-specific CSS
   git add ds-qualifier/generate-pdf.php

   # Update Dockerfile for font installation
   git add Dockerfile

   git commit -m "feat(i18n): Add Japanese language support with font rendering improvements"
   ```

4. **Create PR**
   - Use template from `pr-templates.md` (PR #2 section)
   - Include Japanese screenshots (all pages)
   - Include before/after PDF comparisons
   - Attach sample Japanese and English PDFs
   - Document font metrics generation process
   - Show build performance impact
   - Demonstrate locale switching

5. **Expected Timeline**
   - Create PR: Day 1
   - Review cycles: 1-2 weeks (translations + technical review)
   - May need native Japanese speaker review
   - Address feedback and iterate
   - Merge: End of Week 5-6

---

## 📋 Checklist for Each Phase

### Before Creating Issue
- [ ] Read all documentation files
- [ ] Understand the full scope (i18n infrastructure + Japanese)
- [ ] Prepare answers to likely questions
- [ ] Have screenshots ready showing current hardcoded text
- [ ] Review similar i18n PRs in other projects

### Before Creating PR #1 (i18n Infrastructure)
- [ ] Issue approved by maintainers
- [ ] i18n system design approved
- [ ] Create clean branch from main
- [ ] All hardcoded strings converted to translation keys
- [ ] English translations match original text exactly
- [ ] Test English output is pixel-perfect identical
- [ ] Locale detection works (URL param, session, browser)
- [ ] Prepare before/after screenshots (should be identical)
- [ ] Run all tests
- [ ] README updated with i18n usage info

### Before Creating PR #2 (Japanese Language)
- [ ] PR #1 merged and deployed
- [ ] i18n infrastructure verified working
- [ ] All 330+ translation keys translated to Japanese
- [ ] Translations reviewed by native Japanese speaker
- [ ] Technical terminology verified for accuracy
- [ ] Font metrics generation script tested
- [ ] Japanese PDFs tested (no overflow, proper line breaks)
- [ ] English PDFs regression tested (unchanged)
- [ ] All pages tested in Japanese
- [ ] Locale switching tested (en ↔ ja)
- [ ] Build performance impact measured
- [ ] Sample PDFs prepared (both Japanese and English)
- [ ] Before/after PDF comparison screenshots prepared

---

## 🎯 Success Criteria

### PR #1 Success (i18n Infrastructure)
- ✅ i18n core system implemented and working
- ✅ All English text converted to translation keys
- ✅ Zero visual changes (pixel-perfect comparison)
- ✅ Locale detection works correctly
- ✅ No breaking changes
- ✅ No performance degradation
- ✅ Maintainer approval

### PR #2 Success (Japanese Language)
- ✅ Japanese locale fully functional
- ✅ All 330+ translation keys translated
- ✅ All UI text displays in Japanese correctly
- ✅ Japanese PDFs render perfectly (no overflow, proper line breaks)
- ✅ English locale completely unaffected
- ✅ Locale switching works seamlessly
- ✅ Font metrics generation works in build
- ✅ Build time impact acceptable (~2-3 seconds)
- ✅ Native speaker approved (if possible)
- ✅ Maintainer approval

---

## 💡 Tips for Success

### Communication
- ✅ Be responsive to feedback
- ✅ Explain technical decisions clearly
- ✅ Show empathy for maintainer concerns
- ✅ Provide comprehensive testing evidence

### Technical Quality
- ✅ Keep PRs focused and small
- ✅ Include comprehensive tests
- ✅ Document all changes
- ✅ Follow project conventions

### Process
- ✅ Don't rush between PRs
- ✅ Address all review comments
- ✅ Be patient with review cycles
- ✅ Thank reviewers for their time

---

## 🔗 Related Resources

### External References
- [IPA Font License](https://opensource.org/licenses/IPA)
- [PHP Internationalization](https://www.php.net/manual/en/book.intl.php)
- [Dompdf Documentation](https://github.com/dompdf/dompdf)
- [Adobe Font Metrics Spec](https://adobe-type-tools.github.io/font-tech-notes/pdfs/5004.AFM_Spec.pdf)

### Similar i18n PRs (Examples)
- Look for successful i18n PRs in similar PHP projects
- Study their approach to translation keys
- Learn from their review feedback

---

## ❓ FAQ

**Q: Why split into 2 PRs instead of 1 large PR?**
A: Easier review, clear separation of concerns. PR #1 (i18n infrastructure) is architectural and can be reviewed separately from PR #2 (language-specific content). This also allows partial adoption if needed.

**Q: Why not split PR #2 into separate PRs for translations and font rendering?**
A: Font rendering is specifically needed for Japanese text. Without the font metrics, Japanese PDFs are broken. It makes sense to deliver a complete, working Japanese language experience in one PR rather than delivering a partially-broken feature.

**Q: What if maintainers want a different PR structure?**
A: Be flexible! These are templates. We can adjust based on feedback. Options include:
- Combine into 1 large PR if maintainers prefer
- Keep as 2 PRs but adjust scope
- Split PR #2 if maintainers want translations separate from font work

**Q: What if PR #1 is rejected?**
A: PR #1 provides value independently - it improves code maintainability by eliminating hardcoded strings, even if no additional languages are ever added. But we should work with maintainers to address concerns.

**Q: What if maintainers don't want Japanese language support?**
A: The i18n infrastructure (PR #1) is still valuable. It makes the codebase more maintainable and enables any future translations. We can hold PR #2 until there's demand.

**Q: How long will the full process take?**
A: Realistically 5-6 weeks if all goes smoothly:
- Week 0: Proposal discussion
- Week 1-3: PR #1 review and merge
- Week 4-6: PR #2 review and merge
Be prepared for longer if extensive review is needed or if maintainers request changes.

**Q: Does the upstream currently have i18n support?**
A: No, the upstream does not have i18n infrastructure yet. PR #1 implements it from scratch. This is why PR #1 is larger - it includes the complete i18n system plus English translations.

**Q: Why not use an existing i18n library like gettext?**
A: The proposed solution is simple, lightweight, and PHP-native (no extensions required). However, if maintainers prefer gettext or another library, we can adapt the approach.

---

## 📞 Questions?

If you have questions about using these documents:

1. Review the [Design Document](./i18n-design-and-pr-plan.md) first
2. Check if your question is answered in the templates
3. Reach out to the team for clarification

---

**Good luck with your upstream contribution! 🚀**

Remember: The goal is not just to get code merged, but to make the project better and build a positive relationship with maintainers.
