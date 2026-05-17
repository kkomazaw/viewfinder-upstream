<?php
/**
 * Digital Sovereignty Readiness Assessment - Questions Configuration
 *
 * This file contains the qualifying questions for the readiness assessment
 * Designed for quick 10-15 minute evaluations of digital sovereignty readiness
 *
 * Note: This file now uses translation keys for question text and tooltips.
 * Load i18n before requiring this file to translate question content.
 */

// Load i18n if not already loaded
if (!function_exists('__')) {
    require_once __DIR__ . '/../i18n/I18n.php';
}

return [
    'Data Sovereignty' => [
        'domain_key' => 'Domain-1',
        'name_key' => 'domain.data_sovereignty',
        'description_key' => 'domain.data_sovereignty.description',
        'questions' => [
            [
                'id' => 'ds1',
                'text_key' => 'question.ds1.text',
                'tooltip_key' => 'question.ds1.tooltip',
                'weight' => 1,
            ],
            [
                'id' => 'ds2',
                'text_key' => 'question.ds2.text',
                'tooltip_key' => 'question.ds2.tooltip',
                'weight' => 1,
            ],
            [
                'id' => 'ds3',
                'text_key' => 'question.ds3.text',
                'tooltip_key' => 'question.ds3.tooltip',
                'weight' => 1,
            ]
        ]
    ],

    'Technical Sovereignty' => [
        'domain_key' => 'Domain-2',
        'name_key' => 'domain.technical_sovereignty',
        'description_key' => 'domain.technical_sovereignty.description',
        'questions' => [
            [
                'id' => 'ts1',
                'text_key' => 'question.ts1.text',
                'tooltip_key' => 'question.ts1.tooltip',
                'weight' => 1,
            ],
            [
                'id' => 'ts2',
                'text_key' => 'question.ts2.text',
                'tooltip_key' => 'question.ts2.tooltip',
                'weight' => 1,
            ],
            [
                'id' => 'ts3',
                'text_key' => 'question.ts3.text',
                'tooltip_key' => 'question.ts3.tooltip',
                'weight' => 1,
            ]
        ]
    ],

    'Operational Sovereignty' => [
        'domain_key' => 'Domain-3',
        'name_key' => 'domain.operational_sovereignty',
        'description_key' => 'domain.operational_sovereignty.description',
        'questions' => [
            [
                'id' => 'os1',
                'text_key' => 'question.os1.text',
                'tooltip_key' => 'question.os1.tooltip',
                'weight' => 1,
            ],
            [
                'id' => 'os2',
                'text_key' => 'question.os2.text',
                'tooltip_key' => 'question.os2.tooltip',
                'weight' => 1,
            ],
            [
                'id' => 'os3',
                'text_key' => 'question.os3.text',
                'tooltip_key' => 'question.os3.tooltip',
                'weight' => 1,
            ]
        ]
    ],

    'Assurance Sovereignty' => [
        'domain_key' => 'Domain-4',
        'name_key' => 'domain.assurance_sovereignty',
        'description_key' => 'domain.assurance_sovereignty.description',
        'questions' => [
            [
                'id' => 'as1',
                'text_key' => 'question.as1.text',
                'tooltip_key' => 'question.as1.tooltip',
                'weight' => 1,
            ],
            [
                'id' => 'as2',
                'text_key' => 'question.as2.text',
                'tooltip_key' => 'question.as2.tooltip',
                'weight' => 1,
            ],
            [
                'id' => 'as3',
                'text_key' => 'question.as3.text',
                'tooltip_key' => 'question.as3.tooltip',
                'weight' => 1,
            ]
        ]
    ],

    'Open Source' => [
        'domain_key' => 'Domain-5',
        'name_key' => 'domain.open_source',
        'description_key' => 'domain.open_source.description',
        'questions' => [
            [
                'id' => 'oss1',
                'text_key' => 'question.oss1.text',
                'tooltip_key' => 'question.oss1.tooltip',
                'weight' => 1,
            ],
            [
                'id' => 'oss2',
                'text_key' => 'question.oss2.text',
                'tooltip_key' => 'question.oss2.tooltip',
                'weight' => 1,
            ],
            [
                'id' => 'oss3',
                'text_key' => 'question.oss3.text',
                'tooltip_key' => 'question.oss3.tooltip',
                'weight' => 1,
            ]
        ]
    ],

    'Executive Oversight' => [
        'domain_key' => 'Domain-6',
        'name_key' => 'domain.executive_oversight',
        'description_key' => 'domain.executive_oversight.description',
        'questions' => [
            [
                'id' => 'eo1',
                'text_key' => 'question.eo1.text',
                'tooltip_key' => 'question.eo1.tooltip',
                'weight' => 1,
            ],
            [
                'id' => 'eo2',
                'text_key' => 'question.eo2.text',
                'tooltip_key' => 'question.eo2.tooltip',
                'weight' => 1,
            ],
            [
                'id' => 'eo3',
                'text_key' => 'question.eo3.text',
                'tooltip_key' => 'question.eo3.tooltip',
                'weight' => 1,
            ]
        ]
    ],

    'Managed Services' => [
        'domain_key' => 'Domain-7',
        'name_key' => 'domain.managed_services',
        'description_key' => 'domain.managed_services.description',
        'questions' => [
            [
                'id' => 'ms1',
                'text_key' => 'question.ms1.text',
                'tooltip_key' => 'question.ms1.tooltip',
                'weight' => 1,
            ],
            [
                'id' => 'ms2',
                'text_key' => 'question.ms2.text',
                'tooltip_key' => 'question.ms2.tooltip',
                'weight' => 1,
            ],
            [
                'id' => 'ms3',
                'text_key' => 'question.ms3.text',
                'tooltip_key' => 'question.ms3.tooltip',
                'weight' => 1,
            ]
        ]
    ]
];
