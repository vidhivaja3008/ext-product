<?php
return [
    'ctrl' => [
        'title' => 'LLL:EXT:nitsan_product/Resources/Private/Language/locallang_db.xlf:tx_nitsanproduct_domain_model_product',
        'label' => 'name',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'versioningWS' => true,
        'languageField' => 'sys_language_uid',
        'transOrigPointerField' => 'l10n_parent',
        'transOrigDiffSourceField' => 'l10n_diffsource',
        'delete' => 'deleted',
        'enablecolumns' => [
            'disabled' => 'hidden',
            'starttime' => 'starttime',
            'endtime' => 'endtime',
        ],
        'searchFields' => 'name,description,image,price',
        'iconfile' => 'EXT:nitsan_product/Resources/Public/Icons/tx_nitsanproduct_domain_model_product.gif'
    ],
    'types' => [
        '1' => ['showitem' => 'name, description, image, price, brands, --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:language, sys_language_uid, l10n_parent, l10n_diffsource, --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access, hidden, starttime, endtime'],
    ],
    'columns' => [
        'sys_language_uid' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.language',
            'config' => [
                'type' => 'language',
            ],
        ],
        'l10n_parent' => [
            'displayCond' => 'FIELD:sys_language_uid:>:0',
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.l18n_parent',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'default' => 0,
                'items' => [
                    ['', 0],
                ],
                'foreign_table' => 'tx_nitsanproduct_domain_model_product',
                'foreign_table_where' => 'AND {#tx_nitsanproduct_domain_model_product}.{#pid}=###CURRENT_PID### AND {#tx_nitsanproduct_domain_model_product}.{#sys_language_uid} IN (-1,0)',
            ],
        ],
        'l10n_diffsource' => [
            'config' => [
                'type' => 'passthrough',
            ],
        ],
        'hidden' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.visible',
            'config' => [
                'type' => 'check',
                'renderType' => 'checkboxToggle',
                'items' => [
                    [
                        0 => '',
                        1 => '',
                        'invertStateDisplay' => true
                    ]
                ],
            ],
        ],
        'starttime' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.starttime',
            'config' => [
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'eval' => 'datetime,int',
                'default' => 0,
                'behaviour' => [
                    'allowLanguageSynchronization' => true
                ]
            ],
        ],
        'endtime' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.endtime',
            'config' => [
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'eval' => 'datetime,int',
                'default' => 0,
                'range' => [
                    'upper' => mktime(0, 0, 0, 1, 1, 2038)
                ],
                'behaviour' => [
                    'allowLanguageSynchronization' => true
                ]
            ],
        ],

        'name' => [
            'exclude' => true,
            'label' => 'LLL:EXT:nitsan_product/Resources/Private/Language/locallang_db.xlf:tx_nitsanproduct_domain_model_product.name',
            'description' => 'LLL:EXT:nitsan_product/Resources/Private/Language/locallang_db.xlf:tx_nitsanproduct_domain_model_product.name.description',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim',
                'default' => ''
            ],
        ],
        'description' => [
            'exclude' => true,
            'label' => 'LLL:EXT:nitsan_product/Resources/Private/Language/locallang_db.xlf:tx_nitsanproduct_domain_model_product.description',
            'description' => 'LLL:EXT:nitsan_product/Resources/Private/Language/locallang_db.xlf:tx_nitsanproduct_domain_model_product.description.description',
            'config' => [
                'type' => 'text',
                'cols' => 40,
                'rows' => 15,
                'eval' => 'trim',
                'default' => ''
            ]
        ],
        'image' => [
            'exclude' => true,
            'label' => 'LLL:EXT:nitsan_product/Resources/Private/Language/locallang_db.xlf:tx_nitsanproduct_domain_model_product.image',
            'description' => 'LLL:EXT:nitsan_product/Resources/Private/Language/locallang_db.xlf:tx_nitsanproduct_domain_model_product.image.description',
            'config' => [
                'type' => 'file',
                'maxitems' => 1,
                'allowed' => 'common-image-types',
            ],
        ],
        'price' => [
            'exclude' => true,
            'label' => 'LLL:EXT:nitsan_product/Resources/Private/Language/locallang_db.xlf:tx_nitsanproduct_domain_model_product.price',
            'description' => 'LLL:EXT:nitsan_product/Resources/Private/Language/locallang_db.xlf:tx_nitsanproduct_domain_model_product.price.description',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim',
                'default' => ''
            ],
        ],
        'brands' => [
            'exclude' => true,
            'label' => 'LLL:EXT:nitsan_product/Resources/Private/Language/locallang_db.xlf:tx_nitsanproduct_domain_model_product.brands',
            'description' => 'LLL:EXT:nitsan_product/Resources/Private/Language/locallang_db.xlf:tx_nitsanproduct_domain_model_product.brands.description',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'foreign_table' => 'tx_nitsanproduct_domain_model_brand',
                'minitems' => 0,
                'maxitems' => 1,
            ],

        ],
    
    ],
];
