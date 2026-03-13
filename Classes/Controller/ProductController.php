<?php

declare(strict_types=1);

namespace Nitsan\NitsanProduct\Controller;
use TYPO3\CMS\Core\Resource\File;
use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\DataHandling\SlugHelper;
use TYPO3\CMS\Core\Resource\FileRepository;
use TYPO3\CMS\Core\Resource\ResourceFactory;
use TYPO3\CMS\Core\Messaging\AbstractMessage;
use TYPO3\CMS\Extbase\Persistence\Repository;
use Nitsan\NitsanProduct\Domain\Model\Product;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Core\DataHandling\Model\RecordStateFactory;
use TYPO3\CMS\Core\SysLog\Error as SystemLogErrorClassification;
use Nitsan\NitsanProduct\Domain\Repository\ProductRepository;
use TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager;
use TYPO3\CMS\Extbase\Persistence\Generic\QuerySettingsInterface;
use TYPO3\CMS\Extbase\Persistence\Exception\UnknownObjectException;
use TYPO3\CMS\Extbase\Persistence\Exception\IllegalObjectTypeException;
use TYPO3\CMS\Extbase\Persistence\Generic\Exception\UnsupportedMethodException;


/**
 * This file is part of the "Product" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * (c) 2026 
 */

/**
 * ProductController
 */
class ProductController extends ActionController
{
    /**
     * productRepository
     *
     * @var ProductRepository
     */
    protected $productRepository = null;

    protected $brandRepository = null;

    /**
     * @param ProductRepository $productRepository
     */
    public function injectProductRepository(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function injectBrandRepository(\Nitsan\NitsanProduct\Domain\Repository\BrandRepository $brandRepository){
        $this->brandRepository = $brandRepository;

        
    }

    protected $persistenceManager = null;
    public function injectPersistenceManager(PersistenceManager $persistenceManager): void
    {
        $this->persistenceManager = $persistenceManager;
    }

    public function initializeCreateAction(): void
    {
        if ($this->arguments->hasArgument('newProduct')) {
            $this->arguments->getArgument('newProduct')
                ->getPropertyMappingConfiguration()
                ->skipProperties('image');
        }
    }

    /**
     * action index
     *
     * @return ResponseInterface
     */
    public function indexAction(): ResponseInterface
    {
        return $this->htmlResponse();
    }

    /**
     * action list
     *
     * @return ResponseInterface
     */
    public function listAction(array $filter = []): ResponseInterface
    {
        
        // debug($this->settings);
        $detailPageId = (int)($this->settings['detailPageId'] ?? 0);
        $name = $filter['name'] ?? null;
        $brand = $filter['brand'] ?? null;

        $brands = $this->brandRepository->findAll();
        
        $brandObject = null;

        if ($brand) {
            $brandObject = $this->brandRepository->findByUid((int)$brand);
        }
        if ($brandObject || $name) {
            $products = $this->productRepository->findByFilter($brandObject, $name);
        } else {
            $products = $this->productRepository->findAll();
        }


        $this->view->assignMultiple(
            [
                'products' => $products,
                'brands' => $brands,
                'selectedBrand' => $brand,
                'searchName' => $name
            ]
        );

        // $this->view->assign('products', $products);
        return $this->htmlResponse();
    }

    /**
     * action show
     *
     * @param Product $product
     * @return ResponseInterface
     */
    public function showAction(Product $product): ResponseInterface
    {
        // debug($this->settings);
        $this->view->assign('product', $product);
        return $this->htmlResponse();
    }

    /**
     * action new
     *
     * @return ResponseInterface
     */
    public function newAction(): ResponseInterface
    {
        $brands = $this->brandRepository->findAll();
        $this->view->assignMultiple([
            'brands' => $brands
        ]);
        return $this->htmlResponse();
    }

    /**
     * action create
     *
     * @param Product $newProduct
     */
    public function createAction(Product $newProduct)
    {
    
        try{
            $this->productRepository->add($newProduct);
            $this->persistenceManager->persistAll();
            try{
                $slug = $this->createSlug(
                    'tx_nitsanproduct_domain_model_product',
                    $newProduct);
                $newProduct->setSlug($slug);
                $this->productRepository->update($newProduct);
            }catch(IllegalObjectTypeException  | UnknownObjectException | UnsupportedMethodException  | Error $exception){
                $exceptionArray = [
                    'message' => $exception->getMessage(),
                    'file' =>  $exception->getFile(),
                    'line' =>  $exception->getLine()
                ];
                $newProduct->setHidden(true);
                $this->productRepository->update($newProduct);
            
            }
            if (!empty($_FILES)) {

                $tmpFile = null;
                $fileName = null;

                /**
                 * Frontend plugin structure
                 */
                if (
                    isset($_FILES['tx_nitsanproduct_productlist']['tmp_name']['newProduct']['image']) &&
                    $_FILES['tx_nitsanproduct_productlist']['error']['newProduct']['image'] === 0
                ) {

                    $tmpFile = $_FILES['tx_nitsanproduct_productlist']['tmp_name']['newProduct']['image'];
                    $fileName = $_FILES['tx_nitsanproduct_productlist']['name']['newProduct']['image'];
                }

                /**
                 * Backend module structure
                 */
                elseif (
                    isset($_FILES['newProduct']['tmp_name']['image']) &&
                    $_FILES['newProduct']['error']['image'] === 0
                ) {

                    $tmpFile = $_FILES['newProduct']['tmp_name']['image'];
                    $fileName = $_FILES['newProduct']['name']['image'];
                }

                /**
                 * Upload file if available
                 */
                if ($tmpFile && $fileName) {

                    $newFile = $this->getUploadedFileData($tmpFile, $fileName);

                    if ($newFile) {

                        $fileData = $newFile->getProperties();

                        if (!empty($fileData['uid'])) {

                            $this->productRepository->updateProductImage(
                                (int)$fileData['uid'],
                                (int)$newProduct->getUid(),
                                (int)$newProduct->getPid(),
                                'tx_nitsanproduct_domain_model_product',
                                'image'
                            );
                        }
                    }
                }
            }
        }catch(IllegalObjectTypeException |  Error $exception){
            
        }

        
        
        return $this->redirect('list');
    }

    private function createSlug(string $tableName, Product $product): string
    {
        $fieldName = 'slug';
        $fieldConfig = $GLOBALS['TCA'][$tableName]['columns'][$fieldName]['config'] ?? [];

        $recordData = [
            'uid' => $product->getUid(),
            'pid' => $product->getPid(),
            'slug' => $product->getSlug(),
            'name' => $product->getName()
        ];

        $state = RecordStateFactory::forName($tableName)
            ->fromArray($recordData, $product->getPid(), $product->getUid());

        $slugHelper = GeneralUtility::makeInstance(
            SlugHelper::class,
            $tableName,
            $fieldName,
            $fieldConfig);

        return $product->getSlug() === '' ? $slugHelper->buildSlugForUniqueInTable(
            str_replace('/', '-', $product->getName()), $state) : $product->getSlug();
    }

    /**
     * action edit
     *
     * @param Product $product
     * @TYPO3\CMS\Extbase\Annotation\IgnoreValidation("product")
     * @return ResponseInterface
     */
    public function editAction(Product $product): ResponseInterface
    {
        $brands = $this->brandRepository->findAll();
        $this->view->assignMultiple([
            'product' => $product,
            'brands' => $brands
        ]);
        return $this->htmlResponse();
    }

    /**
     * action update
     *
     * @param Product $product
     */

    public function initializeUpdateAction(): void
    {
        if ($this->arguments->hasArgument('product')) {
            $this->arguments->getArgument('product')
                ->getPropertyMappingConfiguration()
                ->skipProperties('image');
        }
    }
    public function updateAction(Product $product)
    {
        if (!empty($_FILES)) {

                $tmpFile = null;
                $fileName = null;

                /**
                 * Frontend plugin structure
                 */
                if (
                    isset($_FILES['tx_nitsanproduct_productlist']['tmp_name']['product']['image']) &&
                    $_FILES['tx_nitsanproduct_productlist']['error']['product']['image'] === 0
                ) {

                    $tmpFile = $_FILES['tx_nitsanproduct_productlist']['tmp_name']['product']['image'];
                    $fileName = $_FILES['tx_nitsanproduct_productlist']['name']['product']['image'];
                }

                /**
                 * Backend module structure
                 */
                elseif (
                    isset($_FILES['product']['tmp_name']['image']) &&
                    $_FILES['product']['error']['image'] === 0
                ) {

                    $tmpFile = $_FILES['product']['tmp_name']['image'];
                    $fileName = $_FILES['product']['name']['image'];
                }

                /**
                 * Upload file if available
                 */
                if ($tmpFile && $fileName) {

                    $newFile = $this->getUploadedFileData($tmpFile, $fileName);

                    if ($newFile) {

                        $fileData = $newFile->getProperties();

                        if (!empty($fileData['uid'])) {

                            $this->productRepository->updateProductImage(
                                (int)$fileData['uid'],
                                (int)$product->getUid(),
                                (int)$product->getPid(),
                                'tx_nitsanproduct_domain_model_product',
                                'image'
                            );
                        }
                    }
                }
            }
        $this->productRepository->update($product);
        return $this->redirect('list');
}

    /**
     * action delete
     *
     * @param Product $product
     */
    public function deleteAction(Product $product)
    {
        $this->addFlashMessage('The object was deleted. Please be aware that this action is publicly accessible unless you implement an access check. See https://docs.typo3.org/p/friendsoftypo3/extension-builder/master/en-us/User/Index.html', '', AbstractMessage::WARNING);
        $this->productRepository->remove($product);
        $this->redirect('list');
    }

    private function getUploadedFileData(string $tmpName,string $fileName):File {
        $resourceFactory = GeneralUtility::makeInstance(ResourceFactory::class);
        $storage = $resourceFactory->getDefaultStorage();
        $folderPath = $storage->getRootLevelFolder();
        $newFile = $storage->addFile($tmpName,$folderPath,$fileName);
        return $newFile;
    }
}
