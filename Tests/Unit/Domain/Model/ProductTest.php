<?php

declare(strict_types=1);

namespace Nitsan\NitsanProduct\Tests\Unit\Domain\Model;

use PHPUnit\Framework\MockObject\MockObject;
use TYPO3\TestingFramework\Core\AccessibleObjectInterface;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

/**
 * Test case
 */
class ProductTest extends UnitTestCase
{
    /**
     * @var \Nitsan\NitsanProduct\Domain\Model\Product|MockObject|AccessibleObjectInterface
     */
    protected $subject;

    protected function setUp(): void
    {
        parent::setUp();

        $this->subject = $this->getAccessibleMock(
            \Nitsan\NitsanProduct\Domain\Model\Product::class,
            ['dummy']
        );
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }

    /**
     * @test
     */
    public function getNameReturnsInitialValueForString(): void
    {
        self::assertSame(
            '',
            $this->subject->getName()
        );
    }

    /**
     * @test
     */
    public function setNameForStringSetsName(): void
    {
        $this->subject->setName('Conceived at T3CON10');

        self::assertEquals('Conceived at T3CON10', $this->subject->_get('name'));
    }

    /**
     * @test
     */
    public function getDescriptionReturnsInitialValueForString(): void
    {
        self::assertSame(
            '',
            $this->subject->getDescription()
        );
    }

    /**
     * @test
     */
    public function setDescriptionForStringSetsDescription(): void
    {
        $this->subject->setDescription('Conceived at T3CON10');

        self::assertEquals('Conceived at T3CON10', $this->subject->_get('description'));
    }

    /**
     * @test
     */
    public function getImageReturnsInitialValueForString(): void
    {
        self::assertSame(
            '',
            $this->subject->getImage()
        );
    }

    /**
     * @test
     */
    public function setImageForStringSetsImage(): void
    {
        $this->subject->setImage('Conceived at T3CON10');

        self::assertEquals('Conceived at T3CON10', $this->subject->_get('image'));
    }

    /**
     * @test
     */
    public function getPriceReturnsInitialValueForString(): void
    {
        self::assertSame(
            '',
            $this->subject->getPrice()
        );
    }

    /**
     * @test
     */
    public function setPriceForStringSetsPrice(): void
    {
        $this->subject->setPrice('Conceived at T3CON10');

        self::assertEquals('Conceived at T3CON10', $this->subject->_get('price'));
    }

    /**
     * @test
     */
    public function getBrandsReturnsInitialValueForBrand(): void
    {
        $newObjectStorage = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        self::assertEquals(
            $newObjectStorage,
            $this->subject->getBrands()
        );
    }

    /**
     * @test
     */
    public function setBrandsForObjectStorageContainingBrandSetsBrands(): void
    {
        $brand = new \Nitsan\NitsanProduct\Domain\Model\Brand();
        $objectStorageHoldingExactlyOneBrands = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $objectStorageHoldingExactlyOneBrands->attach($brand);
        $this->subject->setBrands($objectStorageHoldingExactlyOneBrands);

        self::assertEquals($objectStorageHoldingExactlyOneBrands, $this->subject->_get('brands'));
    }

    /**
     * @test
     */
    public function addBrandToObjectStorageHoldingBrands(): void
    {
        $brand = new \Nitsan\NitsanProduct\Domain\Model\Brand();
        $brandsObjectStorageMock = $this->getMockBuilder(\TYPO3\CMS\Extbase\Persistence\ObjectStorage::class)
            ->onlyMethods(['attach'])
            ->disableOriginalConstructor()
            ->getMock();

        $brandsObjectStorageMock->expects(self::once())->method('attach')->with(self::equalTo($brand));
        $this->subject->_set('brands', $brandsObjectStorageMock);

        $this->subject->addBrand($brand);
    }

    /**
     * @test
     */
    public function removeBrandFromObjectStorageHoldingBrands(): void
    {
        $brand = new \Nitsan\NitsanProduct\Domain\Model\Brand();
        $brandsObjectStorageMock = $this->getMockBuilder(\TYPO3\CMS\Extbase\Persistence\ObjectStorage::class)
            ->onlyMethods(['detach'])
            ->disableOriginalConstructor()
            ->getMock();

        $brandsObjectStorageMock->expects(self::once())->method('detach')->with(self::equalTo($brand));
        $this->subject->_set('brands', $brandsObjectStorageMock);

        $this->subject->removeBrand($brand);
    }
}
