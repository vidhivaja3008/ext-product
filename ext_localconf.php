<?php
defined('TYPO3') || die();

(static function() {

    // Product List plugin

    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
        'NitsanProduct',
        'Productlist',
        [
            \Nitsan\NitsanProduct\Controller\ProductController::class => 'list,new,create,edit,update'
        ],
        // non-cacheable actions
        [
            \Nitsan\NitsanProduct\Controller\ProductController::class => 'create,update,delete'
        ]
    );

    // Product details plugin 

    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
        'NitsanProduct',
        'Productdetails',
        [
            \Nitsan\NitsanProduct\Controller\ProductController::class => 'show'
        ],
        // non-cacheable actions
        [
            \Nitsan\NitsanProduct\Controller\ProductController::class => 'show'
        ]
    );

    // wizards
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig(
        'mod {
            wizards.newContentElement.wizardItems.plugins {
                elements {

                    productlist {
                        iconIdentifier = nitsanproduct-plugin-productlist
                        title = Product List
                        description = Show list of product
                        tt_content_defValues {
                            CType = list
                            list_type = nitsanproduct_productlist
                        }
                    }

                    productdetails {
                        iconIdentifier = nitsanproduct-plugin-productdetails
                        title = Product Details
                        description = Show details of product
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
