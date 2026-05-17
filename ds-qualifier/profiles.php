<?php
/**
 * Digital Sovereignty Readiness Assessment - Weighting Profiles
 *
 * Defines industry/context-specific weighting profiles for domain scoring
 * Weights: 1.0 = standard, 1.5 = higher priority, 2.0 = critical
 *
 * Note: This file now uses translation keys for names and descriptions.
 * Load i18n before requiring this file to translate profile metadata.
 */

// Load i18n if not already loaded
if (!function_exists('__')) {
    require_once __DIR__ . '/../i18n/I18n.php';
}

return [
    'balanced' => [
        'name_key' => 'profile.balanced.name',
        'description_key' => 'profile.balanced.description',
        'icon' => 'fa-balance-scale',
        'weights' => [
            'Data Sovereignty' => 1.0,
            'Technical Sovereignty' => 1.0,
            'Operational Sovereignty' => 1.0,
            'Assurance Sovereignty' => 1.0,
            'Open Source' => 1.0,
            'Executive Oversight' => 1.0,
            'Managed Services' => 1.0
        ]
    ],

    'financial' => [
        'name_key' => 'profile.financial.name',
        'description_key' => 'profile.financial.description',
        'icon' => 'fa-building-columns',
        'weights' => [
            'Data Sovereignty' => 2.0,      // Critical: PCI DSS, data residency
            'Technical Sovereignty' => 1.0,
            'Operational Sovereignty' => 1.5, // Important: Business continuity
            'Assurance Sovereignty' => 2.0,   // Critical: Audit requirements
            'Open Source' => 1.0,
            'Executive Oversight' => 1.5,     // Important: Governance
            'Managed Services' => 1.5         // Important: Third-party risk
        ]
    ],

    'healthcare' => [
        'name_key' => 'profile.healthcare.name',
        'description_key' => 'profile.healthcare.description',
        'icon' => 'fa-heart-pulse',
        'weights' => [
            'Data Sovereignty' => 2.0,        // Critical: HIPAA, patient data
            'Technical Sovereignty' => 1.0,
            'Operational Sovereignty' => 2.0, // Critical: Patient safety
            'Assurance Sovereignty' => 1.5,   // Important: Compliance
            'Open Source' => 1.0,
            'Executive Oversight' => 1.5,
            'Managed Services' => 1.5
        ]
    ],

    'government' => [
        'name_key' => 'profile.government.name',
        'description_key' => 'profile.government.description',
        'icon' => 'fa-landmark',
        'weights' => [
            'Data Sovereignty' => 2.0,        // Critical: Citizen data
            'Technical Sovereignty' => 1.5,   // Important: Independence
            'Operational Sovereignty' => 1.5, // Important: Continuity
            'Assurance Sovereignty' => 2.0,   // Critical: National security
            'Open Source' => 1.5,             // Important: Transparency
            'Executive Oversight' => 2.0,     // Critical: Accountability
            'Managed Services' => 1.5         // Important: Control
        ]
    ],

    'technology' => [
        'name_key' => 'profile.technology.name',
        'description_key' => 'profile.technology.description',
        'icon' => 'fa-laptop-code',
        'weights' => [
            'Data Sovereignty' => 1.5,
            'Technical Sovereignty' => 2.0,   // Critical: Vendor lock-in
            'Operational Sovereignty' => 1.5, // Important: Scalability
            'Assurance Sovereignty' => 1.0,
            'Open Source' => 2.0,             // Critical: Innovation
            'Executive Oversight' => 1.0,
            'Managed Services' => 1.5         // Important: Multi-cloud
        ]
    ],

    'manufacturing' => [
        'name_key' => 'profile.manufacturing.name',
        'description_key' => 'profile.manufacturing.description',
        'icon' => 'fa-industry',
        'weights' => [
            'Data Sovereignty' => 1.5,        // Important: IP protection
            'Technical Sovereignty' => 1.0,
            'Operational Sovereignty' => 2.0, // Critical: Production uptime
            'Assurance Sovereignty' => 1.5,   // Important: Quality systems
            'Open Source' => 1.0,
            'Executive Oversight' => 1.5,
            'Managed Services' => 2.0         // Critical: OT/IT integration
        ]
    ],

    'telecommunications' => [
        'name_key' => 'profile.telecommunications.name',
        'description_key' => 'profile.telecommunications.description',
        'icon' => 'fa-tower-cell',
        'weights' => [
            'Data Sovereignty' => 2.0,        // Critical: Subscriber data
            'Technical Sovereignty' => 1.5,   // Important: Network independence
            'Operational Sovereignty' => 2.0, // Critical: Service availability
            'Assurance Sovereignty' => 2.0,   // Critical: NIS2, telecoms regulations
            'Open Source' => 1.0,
            'Executive Oversight' => 1.5,
            'Managed Services' => 1.5
        ]
    ],

    'energy' => [
        'name_key' => 'profile.energy.name',
        'description_key' => 'profile.energy.description',
        'icon' => 'fa-bolt',
        'weights' => [
            'Data Sovereignty' => 1.5,
            'Technical Sovereignty' => 1.5,
            'Operational Sovereignty' => 2.0, // Critical: Grid reliability
            'Assurance Sovereignty' => 2.0,   // Critical: Critical infrastructure
            'Open Source' => 1.0,
            'Executive Oversight' => 1.5,
            'Managed Services' => 1.5
        ]
    ],

    'custom' => [
        'name_key' => 'profile.custom.name',
        'description_key' => 'profile.custom.description',
        'icon' => 'fa-sliders',
        'weights' => [
            'Data Sovereignty' => 1.0,
            'Technical Sovereignty' => 1.0,
            'Operational Sovereignty' => 1.0,
            'Assurance Sovereignty' => 1.0,
            'Open Source' => 1.0,
            'Executive Oversight' => 1.0,
            'Managed Services' => 1.0
        ]
    ]
];
