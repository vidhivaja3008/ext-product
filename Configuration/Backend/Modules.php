<?php

return [
    'web_nitsan_product' => [
        'parent' => 'web',
        'position' => [],
        'access' => 'user',
        'path' => '/module/web/nitsan_product',
        'iconIdentifier' => 'module-nitsan-product',
        'labels' => 'LLL:EXT:nitsan_product/Resources/Private/Language/locallang_nitsan_products.xlf',
        'extensionName' => 'NitsanProduct',
        'controllerActions' => [
            \Nitsan\NitsanProduct\Controller\ProductController::class => [
                'new',
                'list',
                'add',
                'edit',
                'update',
            ],
        ],
    ],
];