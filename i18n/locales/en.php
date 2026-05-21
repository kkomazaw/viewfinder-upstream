<?php
/**
 * English (en) Translation File
 * Viewfinder Upstream - Digital Sovereignty Readiness Assessment
 */

return [
    // ========================================
    // Common Elements
    // ========================================
    'common.home' => 'Home',
    'common.back' => 'Back',
    'common.yes' => 'Yes',
    'common.no' => 'No',
    'common.dont_know' => "Don't Know",
    'common.next' => 'Next',
    'common.previous' => 'Previous',
    'common.submit' => 'Submit',
    'common.cancel' => 'Cancel',
    'common.close' => 'Close',
    'common.save' => 'Save',
    'common.reset' => 'Reset',
    'common.loading' => 'Loading...',

    // ========================================
    // Landing Page
    // ========================================
    'landing.title' => 'Digital Sovereignty Navigator',
    'landing.assessment_title' => 'Digital Sovereignty Readiness Assessment',
    'landing.assessment_description' => 'Quick 10-15 minute assessment to evaluate your organization\'s digital sovereignty readiness across 7 key domains',
    'landing.select_profile' => 'Select Your Industry/Context:',
    'landing.start_assessment' => 'Start Assessment',
    'landing.domain_weighting' => 'Domain Weighting',
    'landing.cmmi_levels' => 'CMMI Maturity Levels',
    'landing.customize_weights' => 'Customize Domain Weights',
    'landing.adjust_weights_hint' => 'Adjust weights from 1.0× (standard) to 2.0× (critical priority)',

    // ========================================
    // Profile Names
    // ========================================
    'profile.balanced.name' => 'Balanced',
    'profile.balanced.description' => 'Equal weighting across all domains - suitable for general assessments and organizations without specific regulatory constraints.',

    'profile.financial.name' => 'Financial Services',
    'profile.financial.description' => 'Emphasizes data protection, audit controls, and compliance for banking and finance (PCI DSS, data residency, anti-money laundering).',

    'profile.healthcare.name' => 'Healthcare',
    'profile.healthcare.description' => 'Focuses on patient data protection (HIPAA, GDPR) and operational resilience for life-critical systems requiring 24/7 availability.',

    'profile.government.name' => 'Government & Public Sector',
    'profile.government.description' => 'Comprehensive sovereignty for public sector organizations handling sensitive citizen data and critical national infrastructure (NIS2, FedRAMP).',

    'profile.technology.name' => 'Technology & SaaS',
    'profile.technology.description' => 'Prioritizes technical independence, open source strategy, and multi-cloud portability to avoid vendor lock-in and maintain competitive agility.',

    'profile.manufacturing.name' => 'Manufacturing & Industrial',
    'profile.manufacturing.description' => 'Emphasizes operational resilience, production uptime, and OT/IT integration for continuous operations and IP protection in industrial control systems.',

    'profile.telecommunications.name' => 'Telecommunications',
    'profile.telecommunications.description' => 'Focuses on critical infrastructure protection, subscriber data sovereignty, and 24/7 service availability (NIS2, network security).',

    'profile.energy.name' => 'Energy & Utilities',
    'profile.energy.description' => 'Prioritizes critical infrastructure protection, grid reliability, and SCADA system security for essential services (NIS2, NERC CIP).',

    'profile.custom.name' => 'Custom',
    'profile.custom.description' => 'Define your own domain weightings based on your specific regulatory requirements, business model, and organizational priorities.',

    // ========================================
    // Domain Names
    // ========================================
    'domain.data_sovereignty' => 'Data Sovereignty',
    'domain.technical_sovereignty' => 'Technical Sovereignty',
    'domain.operational_sovereignty' => 'Operational Sovereignty',
    'domain.assurance_sovereignty' => 'Assurance Sovereignty',
    'domain.open_source' => 'Open Source',
    'domain.executive_oversight' => 'Executive Oversight',
    'domain.managed_services' => 'Managed Services',

    // ========================================
    // Domain Descriptions
    // ========================================
    'domain.data_sovereignty.description' => 'Data control, residency, and encryption sovereignty',
    'domain.technical_sovereignty.description' => 'Technology independence and platform portability',
    'domain.operational_sovereignty.description' => 'Operational independence and resilience',
    'domain.assurance_sovereignty.description' => 'Security, compliance, and audit control',
    'domain.open_source.description' => 'Open source strategy and independence',
    'domain.executive_oversight.description' => 'Strategic governance and leadership commitment',
    'domain.managed_services.description' => 'Cloud service control and provider independence',

    // ========================================
    // Maturity Levels
    // ========================================
    'maturity.initial' => 'Initial',
    'maturity.managed' => 'Managed',
    'maturity.defined' => 'Defined',
    'maturity.quantitative' => 'Quantitatively Managed',
    'maturity.optimizing' => 'Optimizing',

    // Maturity Level Descriptions (short)
    'maturity.initial.short' => 'Unpredictable, poorly controlled, reactive processes',
    'maturity.managed.short' => 'Projects planned and executed per policy, basic controls in place',
    'maturity.defined.short' => 'Standardized, documented, and proactive processes organization-wide',
    'maturity.quantitative.short' => 'Measured and controlled using statistical techniques and data',
    'maturity.optimizing.short' => 'Continuous improvement and innovation-focused processes',

    // Maturity Level Descriptions (long)
    'maturity.initial.description' => 'Processes are unpredictable, poorly controlled, and reactive. Your organization has ad-hoc digital sovereignty practices with significant dependencies on external providers. Success depends on individual heroics rather than proven processes.',
    'maturity.managed.description' => 'Projects are planned and executed in accordance with policy. Your organization manages digital sovereignty requirements at the project level, but processes may not be repeatable across the organization. Basic controls are in place but not yet standardized.',
    'maturity.defined.description' => 'Processes are well characterized, understood, and proactive. Your organization has documented and standardized digital sovereignty processes across all domains. Practices are consistent and repeatable, with clear governance structures in place.',
    'maturity.quantitative.description' => 'Processes are measured and controlled using quantitative data. Your organization manages digital sovereignty with statistical and analytical techniques, establishing quantitative objectives for quality and performance. Variations in process performance are understood and controlled.',
    'maturity.optimizing.description' => 'Focus is on continuous improvement and innovation. Your organization continuously improves digital sovereignty processes based on quantitative understanding. You are proactive in identifying and deploying innovative practices, maintaining industry-leading sovereignty posture.',

    // Maturity Level Range
    'maturity.initial.range' => '0-20%',
    'maturity.managed.range' => '21-40%',
    'maturity.defined.range' => '41-60%',
    'maturity.quantitative.range' => '61-80%',
    'maturity.optimizing.range' => '81-100%',

    // ========================================
    // Assessment Page
    // ========================================
    'assessment.title' => 'Digital Sovereignty Readiness Assessment',
    'assessment.subtitle' => 'Quick 10-15 minute assessment to evaluate digital sovereignty readiness',
    'assessment.profile' => 'Profile:',
    'assessment.about_title' => 'About This Tool',
    'assessment.about_description' => 'This lightweight assessment tool helps evaluate your organization\'s digital sovereignty readiness. Answer the questions below based on your current practices and requirements.',
    'assessment.time_required' => 'Time Required:',
    'assessment.time_value' => '10-15 minutes',
    'assessment.questions_count' => 'Questions:',
    'assessment.questions_value' => '21 questions across 7 domains (Yes / No / Don\'t Know)',
    'assessment.output' => 'Output:',
    'assessment.output_value' => 'Readiness score with recommended next steps',
    'assessment.dont_know_hint' => 'Don\'t Know?',
    'assessment.dont_know_explanation' => 'Questions marked "Don\'t Know" will appear as "Questions to Research"',

    // Assessment Buttons
    'assessment.button.next' => 'Next',
    'assessment.button.previous' => 'Previous',
    'assessment.button.complete' => 'Complete Assessment',
    'assessment.button.generate_report' => 'Generate Qualification Report',
    'assessment.button.reset' => 'Reset All Answers',
    'assessment.button.new' => 'New Assessment',

    // ========================================
    // Questions (Data Sovereignty)
    // ========================================
    'question.ds1.text' => 'Does your organization currently comply with all data residency requirements or regulations relevant to your country/region/vertical?',
    'question.ds1.tooltip' => 'Examples: GDPR (EU), PIPEDA (Canada), LGPD (Brazil), industry regulations requiring data to stay within specific jurisdictions.',

    'question.ds2.text' => 'Do you control and manage your encryption keys exclusively (not shared with cloud providers)?',
    'question.ds2.tooltip' => 'BYOK (Bring Your Own Key) or HYOK (Hold Your Own Key) ensures only you can decrypt data, not the cloud provider.',

    'question.ds3.text' => 'Can you prevent sensitive data from crossing specific geographic borders?',
    'question.ds3.tooltip' => 'True cloud portability means workloads can move between providers (e.g. AWS, Azure, local providers, on-prem) without major rewrites.',

    // ========================================
    // Questions (Technical Sovereignty)
    // ========================================
    'question.ts1.text' => 'Can you mitigate vendor lock-in risks with your current technology stack?',
    'question.ts1.tooltip' => 'Vendor lock-in occurs when proprietary technologies make it difficult or expensive to switch providers. Open source and standards-based platforms reduce this risk.',

    'question.ts2.text' => 'Do you prioritize open standards over proprietary APIs in your platforms?',
    'question.ts2.tooltip' => 'Open standards (Kubernetes, OCI containers, POSIX) ensure portability and interoperability. Proprietary APIs create dependencies on specific vendors.',

    'question.ts3.text' => 'Can you migrate critical applications to different cloud platforms if needed?',
    'question.ts3.tooltip' => 'True cloud portability means workloads can move between providers (AWS, Azure, on-prem) without major rewrites.',

    // ========================================
    // Questions (Operational Sovereignty)
    // ========================================
    'question.os1.text' => 'Can you continue operating critical systems if external cloud services become unavailable?',
    'question.os1.tooltip' => 'Operational resilience means critical systems can run independently if cloud providers have outages or service disruptions.',

    'question.os2.text' => 'Do you have in-house technical expertise to manage sovereign infrastructure?',
    'question.os2.tooltip' => 'Managing sovereign systems requires specialized skills in security, compliance, and infrastructure management.',

    'question.os3.text' => 'Do you have disaster recovery plans that account for geopolitical scenarios?',
    'question.os3.tooltip' => 'Geopolitical risks include sanctions, trade restrictions, and data access laws (CLOUD Act, etc.). DR plans should address scenarios where international providers may be restricted.',

    // ========================================
    // Questions (Assurance Sovereignty)
    // ========================================
    'question.as1.text' => 'Do you have the ability to independently verify the security, integrity, and reliability of your digital systems, data, and infrastructure?',
    'question.as1.tooltip' => 'Independently verifying the security of your systems is critical for sovereignty to ensure full control of your data, maintain operational independence, and build trust through auditable, resilient infrastructure.',

    'question.as2.text' => 'Do you control where your security logs and audit trails are stored?',
    'question.as2.tooltip' => 'Security logs contain sensitive information and must meet retention and location requirements. Storing logs with the same vendor creates a single point of failure.',

    'question.as3.text' => 'Are you aware of your country\'s applicable sovereignty related standards?',
    'question.as3.tooltip' => 'Global regulations related to digital sovereignty are still evolving and vary widely but generally focus on a state\'s control over data and technology within its borders. These rules are often motivated by national security, economic interests, and the protection of citizen privacy, and they can significantly impact how companies operate internationally.',

    // ========================================
    // Questions (Open Source)
    // ========================================
    'question.oss1.text' => 'Do you have a formal policy favoring open-source software over proprietary alternatives?',
    'question.oss1.tooltip' => 'Many governments and regulated organizations mandate open source for transparency and sovereignty. Formal policies drive procurement decisions.',

    'question.oss2.text' => 'Can you fork and independently maintain critical open-source dependencies if needed?',
    'question.oss2.tooltip' => 'True software sovereignty means the ability to take ownership if upstream projects change direction or become unavailable.',

    'question.oss3.text' => 'Do you actively contribute to strategic open-source projects important to your operations?',
    'question.oss3.tooltip' => 'Contributing to OSS communities ensures influence over project direction and builds internal expertise.',

    // ========================================
    // Questions (Executive Oversight)
    // ========================================
    'question.eo1.text' => 'Do you have an executive sponsor or steering committee for digital sovereignty initiatives?',
    'question.eo1.tooltip' => 'Executive sponsorship ensures funding, priority, and cross-organizational alignment for digital sovereignty initiatives.',

    'question.eo2.text' => 'Is digital sovereignty explicitly part of your corporate or IT strategy?',
    'question.eo2.tooltip' => 'Strategic commitment to digital sovereignty drives technology choices, vendor selection, and architecture decisions.',

    'question.eo3.text' => 'Do you have a dedicated budget allocated for sovereignty initiatives and compliance?',
    'question.eo3.tooltip' => 'Budget allocation indicates seriousness and enables execution of digital sovereignty programs.',

    // ========================================
    // Questions (Managed Services)
    // ========================================
    'question.ms1.text' => 'Can you restrict cloud deployments to specific regions or certified data centers?',
    'question.ms1.tooltip' => 'Regional restrictions ensure compliance with data residency laws and reduce geopolitical risk.',

    'question.ms2.text' => 'Do you control and monitor your cloud provider\'s administrative access to your systems?',
    'question.ms2.tooltip' => 'Privileged access management ensures only authorized personnel can access systems.',

    'question.ms3.text' => 'Have you tested or validated the ability to migrate workloads to different cloud providers?',
    'question.ms3.tooltip' => 'Regular migration testing proves portability isn\'t just theoretical.',

    // ========================================
    // Results Page
    // ========================================
    'results.title' => 'Digital Sovereignty Readiness Assessment Results',
    'results.assessment_date' => 'Assessment Date:',
    'results.profile' => 'Profile:',
    'results.maturity_level' => 'Maturity Level',
    'results.score' => 'Score',
    'results.of_points' => 'of {max} points',
    'results.raw_score' => 'Raw: {score} pts',

    // Results Sections
    'results.domain_analysis' => 'Domain Analysis',
    'results.domain_analysis.intro' => 'Breakdown of your readiness across the 7 Digital Sovereignty domains:',
    'results.domain_analysis.weights_note' => 'Weights reflect the importance of each domain for the {profile} profile. Domains with higher weights (≥1.5×) contribute more to your overall score.',

    'results.table.domain' => 'Domain',
    'results.table.score' => 'Score',
    'results.table.weight' => 'Weight',
    'results.table.progress' => 'Progress',
    'results.table.maturity' => 'Maturity Level',

    'results.improvement_actions' => 'Recommended Improvement Actions',

    // Improvement Actions - Initial Level
    'improvement.initial.title' => 'Critical Actions for Initial Level',
    'improvement.initial.intro' => 'Processes are unpredictable and reactive. Establish basic digital sovereignty awareness and controls:',
    'improvement.initial.action1' => '<strong>Gain Executive Awareness:</strong> Educate leadership on digital sovereignty risks and regulatory requirements',
    'improvement.initial.action2' => '<strong>Assess Current State:</strong> Conduct inventory of data locations, vendor dependencies, and compliance gaps',
    'improvement.initial.action3' => '<strong>Identify Quick Wins:</strong> Address immediate sovereignty risks (e.g., data residency violations, unencrypted data)',
    'improvement.initial.action4' => '<strong>Secure Resources:</strong> Obtain initial budget and staffing for sovereignty initiatives',
    'improvement.initial.action5' => '<strong>Define Initial Policies:</strong> Create basic policies for data handling and vendor selection',
    'improvement.initial.action6' => '<strong>Build Awareness:</strong> Launch awareness campaigns to educate staff about digital sovereignty',
    'improvement.initial.priorities' => 'Immediate Priorities:',
    'improvement.initial.priority1' => 'Executive sponsorship and steering committee formation',
    'improvement.initial.priority2' => 'Critical data classification and residency mapping',
    'improvement.initial.priority3' => 'Vendor dependency assessment',
    'improvement.initial.priority4' => 'Compliance requirement documentation (GDPR, NIS2, etc.)',

    // Improvement Actions - Managed Level
    'improvement.managed.title' => 'Foundation Actions for Managed Level',
    'improvement.managed.intro' => 'Projects are managed but processes are not yet standardized. Build repeatable practices:',
    'improvement.managed.action1' => '<strong>Develop Strategy:</strong> Create a digital sovereignty roadmap aligned with business objectives',
    'improvement.managed.action2' => '<strong>Implement Controls:</strong> Deploy encryption key management (BYOK/HYOK) and data residency controls',
    'improvement.managed.action3' => '<strong>Establish Governance:</strong> Form sovereignty governance committee with clear responsibilities',
    'improvement.managed.action4' => '<strong>Document Procedures:</strong> Create standard operating procedures for sovereignty-critical activities',
    'improvement.managed.action5' => '<strong>Build Capabilities:</strong> Train technical teams on sovereign technologies and frameworks',
    'improvement.managed.action6' => '<strong>Evaluate Solutions:</strong> Research open-source and sovereign-ready platforms',
    'improvement.managed.focus' => 'Key Focus Areas:',
    'improvement.managed.focus1' => 'Data sovereignty and encryption controls',
    'improvement.managed.focus2' => 'Repeatable assessment processes',
    'improvement.managed.focus3' => 'Vendor risk management framework',
    'improvement.managed.focus4' => 'Compliance tracking and reporting',

    // Improvement Actions - Defined Level
    'improvement.defined.title' => 'Standardization Actions for Defined Level',
    'improvement.defined.intro' => 'Processes are documented and standardized. Focus on organization-wide consistency and optimization:',
    'improvement.defined.action1' => '<strong>Standardize Processes:</strong> Ensure sovereignty practices are consistent across all business units',
    'improvement.defined.action2' => '<strong>Implement Standards:</strong> Adopt open standards and containerization for portability',
    'improvement.defined.action3' => '<strong>Enhance Controls:</strong> Implement advanced monitoring, audit rights, and security log sovereignty',
    'improvement.defined.action4' => '<strong>Build Resilience:</strong> Develop and test disaster recovery plans for geopolitical scenarios',
    'improvement.defined.action5' => '<strong>Expand Open Source:</strong> Increase use of open-source software and contribute to strategic projects',
    'improvement.defined.action6' => '<strong>Pursue Certifications:</strong> Obtain relevant certifications (NIS2, SecNumCloud, FedRAMP, etc.)',
    'improvement.defined.priorities' => 'Advancement Priorities:',
    'improvement.defined.priority1' => 'Process standardization and documentation',
    'improvement.defined.priority2' => 'Cloud platform portability testing',
    'improvement.defined.priority3' => 'Organization-wide training programs',
    'improvement.defined.priority4' => 'Sovereignty metrics and KPIs definition',

    // Improvement Actions - Quantitative Level
    'improvement.quantitative.title' => 'Measurement Actions for Quantitatively Managed Level',
    'improvement.quantitative.intro' => 'Processes are measured and statistically controlled. Optimize through data-driven decisions:',
    'improvement.quantitative.action1' => '<strong>Establish Metrics:</strong> Define and track quantitative sovereignty performance indicators',
    'improvement.quantitative.action2' => '<strong>Analyze Performance:</strong> Use statistical techniques to understand process variations',
    'improvement.quantitative.action3' => '<strong>Set Objectives:</strong> Establish quantitative quality and performance targets for sovereignty',
    'improvement.quantitative.action4' => '<strong>Validate Controls:</strong> Regularly test and measure effectiveness of sovereignty controls',
    'improvement.quantitative.action5' => '<strong>Benchmark Performance:</strong> Compare your metrics against industry standards and peers',
    'improvement.quantitative.action6' => '<strong>Optimize Resources:</strong> Use data to identify and eliminate inefficiencies',
    'improvement.quantitative.focus' => 'Excellence Focus:',
    'improvement.quantitative.focus1' => 'Advanced analytics and metrics dashboards',
    'improvement.quantitative.focus2' => 'Statistical process control techniques',
    'improvement.quantitative.focus3' => 'Continuous monitoring and alerting',
    'improvement.quantitative.focus4' => 'Performance baselines and targets',

    // Improvement Actions - Optimizing Level
    'improvement.optimizing.title' => 'Innovation Actions for Optimizing Level',
    'improvement.optimizing.intro' => 'Focus on continuous improvement and innovation. Lead industry best practices:',
    'improvement.optimizing.action1' => '<strong>Drive Innovation:</strong> Pilot and deploy innovative sovereignty technologies and practices',
    'improvement.optimizing.action2' => '<strong>Continuous Improvement:</strong> Use quantitative feedback to continuously optimize processes',
    'improvement.optimizing.action3' => '<strong>Share Knowledge:</strong> Document and share best practices with industry and open-source communities',
    'improvement.optimizing.action4' => '<strong>Lead Standards:</strong> Contribute to and influence digital sovereignty standards and frameworks',
    'improvement.optimizing.action5' => '<strong>Expand Scope:</strong> Apply sovereignty principles to emerging technologies (AI, edge, quantum)',
    'improvement.optimizing.action6' => '<strong>Stay Ahead:</strong> Proactively monitor and adapt to evolving regulations and geopolitical changes',
    'improvement.optimizing.note' => '<strong>Note:</strong> At the Optimizing level, your focus shifts from implementing controls to driving innovation and thought leadership in digital sovereignty.',

    'results.domain_insights' => 'Domain Insights',
    'results.domain_insights.intro' => 'Review your specific responses across all domains:',
    'results.domain_insights.requirements_identified' => 'Requirements Identified:',
    'results.domain_insights.no_requirements' => 'No Digital Sovereignty requirements were identified in this assessment. Consider focusing on other value propositions.',
    'results.research_questions' => 'Questions to Research',
    'results.research_questions.description' => 'These questions were marked as "Don\'t Know" - research these areas to improve your sovereignty posture:',
    'results.no_research_questions' => 'No questions marked as "Don\'t Know" - excellent knowledge coverage!',

    'results.download_pdf' => 'Download PDF Report',
    'results.take_new' => 'Take New Assessment',

    // ========================================
    // Error Messages
    // ========================================
    'error.file_not_found.title' => 'Resource Not Found',
    'error.file_not_found.message' => 'The requested resource could not be found on the server.',
    'error.file_not_found.what_happened' => 'What happened:',
    'error.file_not_found.what_happened_text' => 'The requested resource could not be found on the server.',
    'error.file_not_found.what_to_do' => 'What you can do:',
    'error.file_not_found.what_to_do_text' => 'Return to the home page and try again. If the problem persists, please contact your administrator with the error ID above.',
    'error.file_not_found.return_home' => 'Return to Home',

    'error.system_error.title' => 'System Error',
    'error.validation_error.title' => 'Validation Error',
    'error.json_error.title' => 'JSON Error',

    'error.error_id' => 'Error ID:',
    'error.timestamp' => 'Timestamp:',

    // ========================================
    // Validation Messages
    // ========================================
    'validation.required' => 'Please answer all questions before proceeding.',
    'validation.unanswered' => 'You have {count} unanswered question(s) in this section.',
    'validation.no_answers' => 'You haven\'t answered any questions. This will result in a score of 0.',
    'validation.confirm_continue' => 'Are you sure you want to continue?',

    // ========================================
    // Notification Messages
    // ========================================
    'notification.progress_saved' => 'Progress saved!',
    'notification.progress_restored' => 'Previous progress restored!',
    'notification.form_reset' => 'Form reset',

    // ========================================
    // Footer / Disclaimer
    // ========================================
    'footer.disclaimer' => 'Disclaimer:',
    'footer.disclaimer_text' => 'This Digital Sovereignty Readiness Assessment Tool is provided by Red Hat for informational purposes only to help organizations review their general sovereign posture. It cannot be used to validate an organization\'s compliance with any specific sovereignty requirements. It is not endorsed by any regulatory authority, and its findings or recommendations do not constitute legal advice. Red Hat bears no legal responsibility or liability for the results or its use. No identity data will be collected or saved.',
    'footer.copyright' => '© {year} Red Hat - Viewfinder Maturity Assessment Tool',

    // ========================================
    // PDF Specific
    // ========================================
    'pdf.title' => 'Digital Sovereignty Readiness Assessment Results',
    'pdf.executive_summary' => 'Executive Summary',
    'pdf.assessment_overview' => 'Assessment Overview',
    'pdf.domain_breakdown' => 'Domain Breakdown',
    'pdf.recommendations' => 'Recommendations',
    'pdf.next_steps' => 'Next Steps',
];
