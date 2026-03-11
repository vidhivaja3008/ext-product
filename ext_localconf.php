<?php
defined('TYPO3') || die();

(static function() {
    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
        'NitsanProduct',
        'Productdetails',
        [
            \Nitsan\NitsanProduct\Controller\ProductController::class => 'list, show'
        ],
        // non-cacheable actions
        [
            \Nitsan\NitsanProduct\Controller\ProductController::class => 'list, show'
        ]
    );

    // wizards
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig(
        'mod {
            wizards.newContentElement.wizardItems.plugins {
                elements {
                    productdetails {
                        iconIdentifier = nitsanproduct-plugin-productdetails
                        title = LLL:EXT:nitsanproduct/Resources/Private/Language/locallang_db.xlf:tx_nitsanproduct_productdetails.name
                        description = LLL:EXT:nitsan_product/Resources/Private/Language/locallang_db.xlf:tx_nitsanproduct_productdetails.description
                        tt_content_defValues {
                            CType = list
                            list_type = nitsanproduct_productdetails
                        }
                    }
                }
                show = *
            }
       }'
    );
})();
