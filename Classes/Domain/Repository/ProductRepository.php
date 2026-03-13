<?php

declare(strict_types=1);

namespace Nitsan\NitsanProduct\Domain\Repository;

use PDO;
use Doctrine\DBAL\ParameterType;
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
    public function initializeObject(): void
    {
        $querySettings = $this->createQuery()->getQuerySettings();
        $querySettings->setRespectStoragePage(false);
    }

    public function findByFilter($brand = null, $name = null)
    {
        $query = $this->createQuery();
        $constraints = [];

        if ($brand) {
            $constraints[] = $query->equals('brands.uid', (int)$brand->getUid());
        }

        if ($name) {
            $constraints[] = $query->like('name', '%' . $name . '%');
        }

        if (count($constraints) === 1) {
            $query->matching($constraints[0]);
        } elseif (count($constraints) > 1) {
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

  public function updateProductImage(
                int $fileUid,
                int $productUid,
                int $pid,
                string $table,
                string $field
            ): void {

                $connection = GeneralUtility::makeInstance(ConnectionPool::class)
                    ->getConnectionForTable('sys_file_reference');

                // Step 1: Remove old image relation
                $connection->delete(
                    'sys_file_reference',
                    [
                        'uid_foreign' => $productUid,
                        'tablenames'  => $table,
                        'fieldname'   => $field
                    ]
                );

                // Step 2: Insert new relation
                $connection->insert(
                    'sys_file_reference',
                    [
                        'uid_local'        => $fileUid,
                        'uid_foreign'      => $productUid,
                        'tablenames'       => $table,
                        'fieldname'        => $field,
                        'pid'              => $pid,
                        'sorting_foreign'  => 1,
                        'tstamp'           => time(),
                        'crdate'           => time(),
                        'deleted'          => 0,
                        'hidden'           => 0
                    ]
                );
    }

    public function getRefrenceImageCounts($ref, $uid_foreign, $field, $table)
    {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getQueryBuilderForTable('sys_file_reference');
        $lastRecord = $queryBuilder
            ->select('*')
            ->from('sys_file_reference')
            ->where(
                $queryBuilder->expr()->eq('uid_foreign', $queryBuilder->createNamedParameter($uid_foreign, ParameterType::INTEGER)),
                $queryBuilder->expr()->eq('tablenames', $queryBuilder->createNamedParameter($table)),
                $queryBuilder->expr()->eq('fieldname', $queryBuilder->createNamedParameter($field)),
                $queryBuilder->expr()->eq('deleted', $queryBuilder->createNamedParameter(0, ParameterType::INTEGER))
            )
            ->executeQuery()
            ->fetchAllAssociative();
        return count($lastRecord);
    }


}
