<?php 

    return [
        'frontend' => [
            'Nitsan/nitsan_product/product-middleware' => [
                'target' => \Nitsan\NitsanProduct\Middleware\ProductMiddleware::class,
                'after' => [
                    'typo3/cms-frontend/page-resolver'
                ],
                'before' => [
                    'typo3/cms-frontend/prepare-tsfe-rendering'
                ],
            ],
        ],

    ];