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
class BrandControllerTest extends UnitTestCase
{
    /**
     * @var \Nitsan\NitsanProduct\Controller\BrandController|MockObject|AccessibleObjectInterface
     */
    protected $subject;

    protected function setUp(): void
    {
        parent::setUp();
        $this->subject = $this->getMockBuilder($this->buildAccessibleProxy(\Nitsan\NitsanProduct\Controller\BrandController::class))
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
    public function listActionFetchesAllBrandsFromRepositoryAndAssignsThemToView(): void
    {
        $allBrands = $this->getMockBuilder(\TYPO3\CMS\Extbase\Persistence\ObjectStorage::class)
            ->disableOriginalConstructor()
            ->getMock();

        $brandRepository = $this->getMockBuilder(\Nitsan\NitsanProduct\Domain\Repository\BrandRepository::class)
            ->onlyMethods(['findAll'])
            ->disableOriginalConstructor()
            ->getMock();
        $brandRepository->expects(self::once())->method('findAll')->will(self::returnValue($allBrands));
        $this->subject->_set('brandRepository', $brandRepository);

        $view = $this->getMockBuilder(ViewInterface::class)->getMock();
        $view->expects(self::once())->method('assign')->with('brands', $allBrands);
        $this->subject->_set('view', $view);

        $this->subject->listAction();
    }

    /**
     * @test
     */
    public function showActionAssignsTheGivenBrandToView(): void
    {
        $brand = new \Nitsan\NitsanProduct\Domain\Model\Brand();

        $view = $this->getMockBuilder(ViewInterface::class)->getMock();
        $this->subject->_set('view', $view);
        $view->expects(self::once())->method('assign')->with('brand', $brand);

        $this->subject->showAction($brand);
    }

    /**
     * @test
     */
    public function createActionAddsTheGivenBrandToBrandRepository(): void
    {
        $brand = new \Nitsan\NitsanProduct\Domain\Model\Brand();

        $brandRepository = $this->getMockBuilder(\Nitsan\NitsanProduct\Domain\Repository\BrandRepository::class)
            ->onlyMethods(['add'])
            ->disableOriginalConstructor()
            ->getMock();

        $brandRepository->expects(self::once())->method('add')->with($brand);
        $this->subject->_set('brandRepository', $brandRepository);

        $this->subject->createAction($brand);
    }

    /**
     * @test
     */
    public function editActionAssignsTheGivenBrandToView(): void
    {
        $brand = new \Nitsan\NitsanProduct\Domain\Model\Brand();

        $view = $this->getMockBuilder(ViewInterface::class)->getMock();
        $this->subject->_set('view', $view);
        $view->expects(self::once())->method('assign')->with('brand', $brand);

        $this->subject->editAction($brand);
    }

    /**
     * @test
     */
    public function updateActionUpdatesTheGivenBrandInBrandRepository(): void
    {
        $brand = new \Nitsan\NitsanProduct\Domain\Model\Brand();

        $brandRepository = $this->getMockBuilder(\Nitsan\NitsanProduct\Domain\Repository\BrandRepository::class)
            ->onlyMethods(['update'])
            ->disableOriginalConstructor()
            ->getMock();

        $brandRepository->expects(self::once())->method('update')->with($brand);
        $this->subject->_set('brandRepository', $brandRepository);

        $this->subject->updateAction($brand);
    }

    /**
     * @test
     */
    public function deleteActionRemovesTheGivenBrandFromBrandRepository(): void
    {
        $brand = new \Nitsan\NitsanProduct\Domain\Model\Brand();

        $brandRepository = $this->getMockBuilder(\Nitsan\NitsanProduct\Domain\Repository\BrandRepository::class)
            ->onlyMethods(['remove'])
            ->disableOriginalConstructor()
            ->getMock();

        $brandRepository->expects(self::once())->method('remove')->with($brand);
        $this->subject->_set('brandRepository', $brandRepository);

        $this->subject->deleteAction($brand);
    }
}
