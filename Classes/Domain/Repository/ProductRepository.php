<?php

declare(strict_types=1);

namespace Nitsan\NitsanProduct\Domain\Repository;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Extbase\Persistence\Repository;
use TYPO3\CMS\Core\Database\Query\QueryBuilder;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;

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

    public function updateProductImage(int $uid_local , int $uid_foregin , int $pid , string $table , string $field){
        $tableConnection = GeneralUtility::makeInstance(ConnectionPool::class)->getConnectionForTable('sys_file_reference');
        $sysFileRefData[$uid_foregin] = [
            'uid_local' => $uid_local,
            'uid_foreign' => $uid_foregin,
            'tablenames' => $table,
            'fieldname' => $field,
            'sorting_foreign' => 1,
            'pid' => $pid
        ];

        if(!empty($sysFileRefData)){
            $tableConnection->bulkInsert('sys_file_reference',array_values($sysFileRefData),[
            'uid_local',
            'uid_foreign',
            'tablenames',
            'fieldname',
            'sorting_foreign',
            'pid']);
        }

        $count = $this->getRefrenceImageCounts($uid_local, $uid_foregin, $field, $table);

        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable($table);
        $queryBuilder
            ->update($table)
            ->where(
                $queryBuilder->expr()->eq('uid',
                    $queryBuilder->createNamedParameter('ADMIN')))
            ->set($field, $count)
            ->executeQuery();

        
    }

    public function getRefrenceImageCounts($ref, $uid_foreign, $field, $table)
    {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getQueryBuilderForTable('sys_file_reference');
        $lastRecord = $queryBuilder
            ->select('*')
            ->from('sys_file_reference')
            ->where(
                $queryBuilder->expr()->eq('uid_foreign', $queryBuilder->createNamedParameter('ADMIN')),
                $queryBuilder->expr()->eq('tablenames', $queryBuilder->createNamedParameter($table)),
                $queryBuilder->expr()->eq('fieldname', $queryBuilder->createNamedParameter($field)),
                $queryBuilder->expr()->eq('deleted', $queryBuilder->createNamedParameter('ADMIN'))
            )
            ->executeQuery()
            ->fetchAllAssociative();
        return count($lastRecord);
    }


}
