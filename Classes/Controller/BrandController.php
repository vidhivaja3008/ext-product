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
 * BrandController
 */
class BrandController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{

    /**
     * brandRepository
     *
     * @var \Nitsan\NitsanProduct\Domain\Repository\BrandRepository
     */
    protected $brandRepository = null;

    /**
     * @param \Nitsan\NitsanProduct\Domain\Repository\BrandRepository $brandRepository
     */
    public function injectBrandRepository(\Nitsan\NitsanProduct\Domain\Repository\BrandRepository $brandRepository)
    {
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
    public function listAction(): \Psr\Http\Message\ResponseInterface
    {
        $brands = $this->brandRepository->findAll();
        $this->view->assign('brands', $brands);
        return $this->htmlResponse();
    }

    /**
     * action show
     *
     * @param \Nitsan\NitsanProduct\Domain\Model\Brand $brand
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function showAction(\Nitsan\NitsanProduct\Domain\Model\Brand $brand): \Psr\Http\Message\ResponseInterface
    {
        $this->view->assign('brand', $brand);
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
     * @param \Nitsan\NitsanProduct\Domain\Model\Brand $newBrand
     */
    public function createAction(\Nitsan\NitsanProduct\Domain\Model\Brand $newBrand)
    {
        $this->addFlashMessage('The object was created. Please be aware that this action is publicly accessible unless you implement an access check. See https://docs.typo3.org/p/friendsoftypo3/extension-builder/master/en-us/User/Index.html', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::WARNING);
        $this->brandRepository->add($newBrand);
        $this->redirect('list');
    }

    /**
     * action edit
     *
     * @param \Nitsan\NitsanProduct\Domain\Model\Brand $brand
     * @TYPO3\CMS\Extbase\Annotation\IgnoreValidation("brand")
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function editAction(\Nitsan\NitsanProduct\Domain\Model\Brand $brand): \Psr\Http\Message\ResponseInterface
    {
        $this->view->assign('brand', $brand);
        return $this->htmlResponse();
    }

    /**
     * action update
     *
     * @param \Nitsan\NitsanProduct\Domain\Model\Brand $brand
     */
    public function updateAction(\Nitsan\NitsanProduct\Domain\Model\Brand $brand)
    {
        $this->addFlashMessage('The object was updated. Please be aware that this action is publicly accessible unless you implement an access check. See https://docs.typo3.org/p/friendsoftypo3/extension-builder/master/en-us/User/Index.html', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::WARNING);
        $this->brandRepository->update($brand);
        $this->redirect('list');
    }

    /**
     * action delete
     *
     * @param \Nitsan\NitsanProduct\Domain\Model\Brand $brand
     */
    public function deleteAction(\Nitsan\NitsanProduct\Domain\Model\Brand $brand)
    {
        $this->addFlashMessage('The object was deleted. Please be aware that this action is publicly accessible unless you implement an access check. See https://docs.typo3.org/p/friendsoftypo3/extension-builder/master/en-us/User/Index.html', '', \TYPO3\CMS\Core\Messaging\AbstractMessage::WARNING);
        $this->brandRepository->remove($brand);
        $this->redirect('list');
    }
}
