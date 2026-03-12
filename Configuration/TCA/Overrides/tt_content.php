<?php
defined('TYPO3') || die();

// Product List
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
    'NitsanProduct',
    'Productlist',
    'Product List'
);

// Product Detail
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
    'NitsanProduct',
    'Productdetails',
    'Product Details'
);

$pluginSignature = str_replace('_', '', 'nitsan_product') . '_productlist';
$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist'][$pluginSignature] = 'pi_flexform';
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue(
    $pluginSignature,
    'FILE:EXT:nitsan_product/Configuration/FlexForms/FlexFormProductList.xml');

$pluginSignatureDetails = 'nitsanproduct_productdetails';

$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist'][$pluginSignatureDetails] = 'pi_flexform';

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue(
    $pluginSignatureDetails,
    'FILE:EXT:nitsan_product/Configuration/FlexForms/FlexFormProductDetails.xml'
);
