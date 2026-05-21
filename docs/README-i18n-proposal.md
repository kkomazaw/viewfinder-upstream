# Internationalization Proposal Documentation

This directory contains the complete documentation for proposing Japanese language support to upstream maintainers.

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
- Overview of 3-PR plan
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
- Template for PR #1 (Translation Keys)
- Template for PR #2 (Japanese Translations)
- Template for PR #3 (Font Rendering)
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
   # Title: "[PROPOSAL] Add Japanese Language Support with Font Rendering Improvements"
   ```

2. **Get Feedback**
   - Tag maintainers for feedback
   - Answer questions
   - Adjust approach based on comments
   - Get approval to proceed with PRs

3. **Expected Outcome**
   - ✅ Maintainers approve 3-PR approach
   - ✅ Any concerns addressed
   - ✅ Green light to start PR #1

---

### Step 2: PR #1 - Translation Keys (Week 1)

1. **Create Feature Branch**
   ```bash
   git checkout -b feat/i18n-translation-keys
   ```

2. **Cherry-pick Relevant Changes**
   ```bash
   # Extract only translation key changes from current commit
   git show 90fc170 -- i18n/locales/en.php > /tmp/en.patch
   git show 90fc170 -- ds-qualifier/generate-pdf.php > /tmp/pdf.patch
   git show 90fc170 -- ds-qualifier/results.php > /tmp/results.patch

   # Review and apply only translation key additions
   ```

3. **Create PR**
   - Use template from `pr-templates.md` (PR #1 section)
   - Include before/after screenshots (should be identical)
   - Add test results
   - Reference design doc for details

4. **Expected Timeline**
   - Create PR: Day 1
   - Review cycles: 3-5 days
   - Merge: End of Week 1

---

### Step 3: PR #2 - Japanese Translations (Week 2)

1. **Wait for PR #1 Merge**
   - Don't start until PR #1 is merged
   - Base branch on merged PR #1

2. **Create Feature Branch**
   ```bash
   git checkout main
   git pull
   git checkout -b feat/i18n-japanese-translations
   ```

3. **Add Japanese File Only**
   ```bash
   # Copy ja.php from current implementation
   cp i18n/locales/ja.php /tmp/ja.php

   # Create clean commit with only ja.php
   git add i18n/locales/ja.php
   git commit -m "feat(i18n): Add Japanese translation file (ja.php)"
   ```

4. **Create PR**
   - Use template from `pr-templates.md` (PR #2 section)
   - Include Japanese screenshots
   - Demonstrate locale switching
   - Note: May need native speaker review

5. **Expected Timeline**
   - Create PR: Day 1
   - Review cycles: 3-5 days (may need translation review)
   - Merge: End of Week 2

---

### Step 4: PR #3 - Font Rendering (Week 3-4)

1. **Wait for PR #2 Merge**
   - Ensure Japanese translations are in place
   - Base branch on merged PR #2

2. **Create Feature Branch**
   ```bash
   git checkout main
   git pull
   git checkout -b fix/japanese-pdf-font-rendering
   ```

3. **Add Font-Related Changes**
   ```bash
   # Add generate-font-metrics.php
   git add generate-font-metrics.php

   # Add PDF generation improvements
   git add ds-qualifier/generate-pdf.php

   # Add Dockerfile changes
   git add Dockerfile

   git commit -m "fix(pdf): Add font metrics extraction for Japanese text rendering"
   ```

4. **Create PR**
   - Use template from `pr-templates.md` (PR #3 section)
   - Include before/after PDF screenshots
   - Attach sample PDF files
   - Document performance impact
   - Explain font metrics extraction

5. **Expected Timeline**
   - Create PR: Day 1
   - Review cycles: 5-10 days (more complex, technical)
   - Merge: End of Week 3-4

---

## 📋 Checklist for Each Phase

### Before Creating Issue
- [ ] Read all three documents
- [ ] Understand the full scope
- [ ] Prepare answers to likely questions
- [ ] Have screenshots ready
- [ ] Review similar i18n PRs in other projects

### Before Creating PR #1
- [ ] Issue approved by maintainers
- [ ] Create clean branch from main
- [ ] Test English output is identical
- [ ] Prepare before/after screenshots
- [ ] Run all tests

### Before Creating PR #2
- [ ] PR #1 merged
- [ ] Translations reviewed by native speaker
- [ ] All pages tested in Japanese
- [ ] Locale switching tested
- [ ] Screenshots prepared

### Before Creating PR #3
- [ ] PR #2 merged
- [ ] Font metrics generation tested
- [ ] Japanese PDFs tested (no overflow)
- [ ] English PDFs tested (unchanged)
- [ ] Sample PDFs attached
- [ ] Performance benchmarks run

---

## 🎯 Success Criteria

### PR #1 Success
- ✅ All English text uses translation keys
- ✅ Zero visual changes
- ✅ No breaking changes
- ✅ Maintainer approval

### PR #2 Success
- ✅ Japanese locale fully functional
- ✅ All UI text translated
- ✅ English locale unaffected
- ✅ Native speaker approved (if possible)

### PR #3 Success
- ✅ Japanese PDFs render perfectly
- ✅ No text overflow or character issues
- ✅ English PDFs unchanged
- ✅ Build process stable

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

**Q: Why split into 3 PRs instead of 1 large PR?**
A: Easier review, clear separation of concerns, allows partial adoption if needed.

**Q: What if maintainers want a different PR structure?**
A: Be flexible! These are templates. Adjust based on feedback.

**Q: What if PR #1 or #2 is rejected?**
A: Each PR provides value independently. PR #1 improves code quality regardless of language support.

**Q: How long will the full process take?**
A: Realistically 4-6 weeks if all goes smoothly. Be prepared for longer if extensive review is needed.

**Q: Can we skip PR #3 if only web UI matters?**
A: Yes, but Japanese PDFs will have rendering issues. Recommend including it for complete solution.

---

## 📞 Questions?

If you have questions about using these documents:

1. Review the [Design Document](./i18n-design-and-pr-plan.md) first
2. Check if your question is answered in the templates
3. Reach out to the team for clarification

---

**Good luck with your upstream contribution! 🚀**

Remember: The goal is not just to get code merged, but to make the project better and build a positive relationship with maintainers.
