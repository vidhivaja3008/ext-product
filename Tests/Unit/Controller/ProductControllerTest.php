<?php

declare(strict_types=1);

namespace Nitsan\NitsanProduct\Tests\Unit\Controller;

use PHPUnit\Framework\MockObject\MockObject;
use TYPO3\TestingFramework\Core\AccessibleObjectInterface;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;
use TYPO3Fluid\Fluid\View\ViewInterface;

/**
 * Test case
 */
class ProductControllerTest extends UnitTestCase
{
    /**
     * @var \Nitsan\NitsanProduct\Controller\ProductController|MockObject|AccessibleObjectInterface
     */
    protected $subject;

    protected function setUp(): void
    {
        parent::setUp();
        $this->subject = $this->getMockBuilder($this->buildAccessibleProxy(\Nitsan\NitsanProduct\Controller\ProductController::class))
            ->onlyMethods(['redirect', 'forward', 'addFlashMessage'])
            ->disableOriginalConstructor()
            ->getMock();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }

    /**
     * @test
     */
    public function listActionFetchesAllProductsFromRepositoryAndAssignsThemToView(): void
    {
        $allProducts = $this->getMockBuilder(\TYPO3\CMS\Extbase\Persistence\ObjectStorage::class)
            ->disableOriginalConstructor()
            ->getMock();

        $productRepository = $this->getMockBuilder(\Nitsan\NitsanProduct\Domain\Repository\ProductRepository::class)
            ->onlyMethods(['findAll'])
            ->disableOriginalConstructor()
            ->getMock();
        $productRepository->expects(self::once())->method('findAll')->will(self::returnValue($allProducts));
        $this->subject->_set('productRepository', $productRepository);

        $view = $this->getMockBuilder(ViewInterface::class)->getMock();
        $view->expects(self::once())->method('assign')->with('products', $allProducts);
        $this->subject->_set('view', $view);

        $this->subject->listAction();
    }

    /**
     * @test
     */
    public function showActionAssignsTheGivenProductToView(): void
    {
        $product = new \Nitsan\NitsanProduct\Domain\Model\Product();

        $view = $this->getMockBuilder(ViewInterface::class)->getMock();
        $this->subject->_set('view', $view);
        $view->expects(self::once())->method('assign')->with('product', $product);

        $this->subject->showAction($product);
    }

    /**
     * @test
     */
    public function createActionAddsTheGivenProductToProductRepository(): void
    {
        $product = new \Nitsan\NitsanProduct\Domain\Model\Product();

        $productRepository = $this->getMockBuilder(\Nitsan\NitsanProduct\Domain\Repository\ProductRepository::class)
            ->onlyMethods(['add'])
            ->disableOriginalConstructor()
            ->getMock();

        $productRepository->expects(self::once())->method('add')->with($product);
        $this->subject->_set('productRepository', $productRepository);

        $this->subject->createAction($product);
    }

    /**
     * @test
     */
    public function editActionAssignsTheGivenProductToView(): void
    {
        $product = new \Nitsan\NitsanProduct\Domain\Model\Product();

        $view = $this->getMockBuilder(ViewInterface::class)->getMock();
        $this->subject->_set('view', $view);
        $view->expects(self::once())->method('assign')->with('product', $product);

        $this->subject->editAction($product);
    }

    /**
     * @test
     */
    public function updateActionUpdatesTheGivenProductInProductRepository(): void
    {
        $product = new \Nitsan\NitsanProduct\Domain\Model\Product();

        $productRepository = $this->getMockBuilder(\Nitsan\NitsanProduct\Domain\Repository\ProductRepository::class)
            ->onlyMethods(['update'])
            ->disableOriginalConstructor()
            ->getMock();

        $productRepository->expects(self::once())->method('update')->with($product);
        $this->subject->_set('productRepository', $productRepository);

        $this->subject->updateAction($product);
    }

    /**
     * @test
     */
    public function deleteActionRemovesTheGivenProductFromProductRepository(): void
    {
        $product = new \Nitsan\NitsanProduct\Domain\Model\Product();

        $productRepository = $this->getMockBuilder(\Nitsan\NitsanProduct\Domain\Repository\ProductRepository::class)
            ->onlyMethods(['remove'])
            ->disableOriginalConstructor()
            ->getMock();

        $productRepository->expects(self::once())->method('remove')->with($product);
        $this->subject->_set('productRepository', $productRepository);

        $this->subject->deleteAction($product);
    }
}
