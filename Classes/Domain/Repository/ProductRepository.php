<?php

declare(strict_types=1);

namespace Nitsan\NitsanProduct\Domain\Repository;

use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Extbase\Persistence\Repository;


/**
 * This file is part of the "Product" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * (c) 2026 
 */

/**
 * The repository for Products
 */


class ProductRepository extends Repository
{

    protected ConnectionPool $connectionPool;

    public function __construct(ConnectionPool $connectionPool)
    {
        $this->connectionPool = $connectionPool;
        parent::__construct();
    }

    public function findByFilter($brand = null, $name= null){
        $query = $this->createQuery();

        $constraints = [];

        if($brand){
            $constraints[] = $query->equals('brands',$brand);
        }
        if($name){
            $constraints[] = $query->like('name','%'. $name .'%');
        }

        if(!empty($constraints)){
            $query->matching(
                $query->logicalAnd(...$constraints)
            );
        }
        return $query->execute();
    }

    public function productDetails():array
    {
        
        $queryBuilder = $this->connectionPool->getQueryBuilderForTable('tx_nitsanproduct_domain_model_product');
        $result = $queryBuilder->select('p.*','b.name AS brand_name')->from('tx_nitsanproduct_domain_model_product' ,'p')
                                              ->leftJoin('p' , 
                                                        'tx_nitsanproduct_domain_model_brand',
                                                        'b',
                                                        $queryBuilder->expr()->eq('p.brands',$queryBuilder->quoteIdentifier('b.uid'))
                                                        )
                                              ->where(
                                                $queryBuilder->expr()->eq('p.hidden', $queryBuilder->createNamedParameter('admin'))
                                              )->executeQuery()->fetchAllAssociative();
        return $result;

    }
}
