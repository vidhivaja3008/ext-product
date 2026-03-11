<?php

declare(strict_types=1);

namespace Nitsan\NitsanProduct\Controller;


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
class ProductController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{

    /**
     * productRepository
     *
     * @var \Nitsan\NitsanProduct\Domain\Repository\ProductRepository
     */
    protected $productRepository = null;

    protected $brandRepository = null;

    /**
     * @param \Nitsan\NitsanProduct\Domain\Repository\ProductRepository $productRepository
     */
    public function injectProductRepository(\Nitsan\NitsanProduct\Domain\Repository\ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function injectBrandRepository(\Nitsan\NitsanProduct\Domain\Repository\BrandRepository $brandRepository){
        $this->brandRepository = $brandRepository;
    }

    /**
     * action index
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function indexAction(): \Psr\Http\Message\ResponseInterface
    {
        return $this->htmlResponse();
    }

    /**
     * action list
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function listAction(array $filter = []): \Psr\Http\Message\ResponseInterface
    {
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
     * @param \Nitsan\NitsanProduct\Domain\Model\Product $product
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function showAction(\Nitsan\NitsanProduct\Domain\Model\Product $product): \Psr\Http\Message\ResponseInterface
    {
        $this->view->assign('product', $product);
        return $this->htmlResponse();
    }

    /**
     * action new
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function newAction(): \Psr\Http\Message\ResponseInterface
    {
        return $this->htmlResponse();
    }

    /**
     * action create
     *
     * @param \Nitsan\NitsanProduct\Domain\Model\Product $newProduct
     */
    public function createAction(\Nitsan\NitsanProduct\Domain\Model\Product $newProduct)
    {
        $this->addFlashMessage('The object was created. Please be aware that this action is publicly accessible unless you implement an access check. See https://docs.typo3.org/p/friendsoftypo3/extension-builder/master/en-us/User/Index.html', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::WARNING);
        $this->productRepository->add($newProduct);
        $this->redirect('list');
    }

    /**
     * action edit
     *
     * @param \Nitsan\NitsanProduct\Domain\Model\Product $product
     * @TYPO3\CMS\Extbase\Annotation\IgnoreValidation("product")
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function editAction(\Nitsan\NitsanProduct\Domain\Model\Product $product): \Psr\Http\Message\ResponseInterface
    {
        $this->view->assign('product', $product);
        return $this->htmlResponse();
    }

    /**
     * action update
     *
     * @param \Nitsan\NitsanProduct\Domain\Model\Product $product
     */
    public function updateAction(\Nitsan\NitsanProduct\Domain\Model\Product $product)
    {
        $this->addFlashMessage('The object was updated. Please be aware that this action is publicly accessible unless you implement an access check. See https://docs.typo3.org/p/friendsoftypo3/extension-builder/master/en-us/User/Index.html', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::WARNING);
        $this->productRepository->update($product);
        $this->redirect('list');
    }

    /**
     * action delete
     *
     * @param \Nitsan\NitsanProduct\Domain\Model\Product $product
     */
    public function deleteAction(\Nitsan\NitsanProduct\Domain\Model\Product $product)
    {
        $this->addFlashMessage('The object was deleted. Please be aware that this action is publicly accessible unless you implement an access check. See https://docs.typo3.org/p/friendsoftypo3/extension-builder/master/en-us/User/Index.html', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::WARNING);
        $this->productRepository->remove($product);
        $this->redirect('list');
    }
}
