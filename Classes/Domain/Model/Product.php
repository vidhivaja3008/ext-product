<?php

declare(strict_types=1);

namespace Nitsan\NitsanProduct\Domain\Model;


/**
 * This file is part of the "Product" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * (c) 2026 
 */

/**
 * Product
 */
class Product extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
{

    /**
     * name
     *
     * @var string
     */
    protected $name = null;

    /**
     * description
     *
     * @var string
     */
    protected $description = null;

    /**
     * image
     *
     * @var  \TYPO3\CMS\Extbase\Domain\Model\FileReference
     */
    protected $image = null;

    /**
     * price
     *
     * @var string
     */
    protected $price = null;

    /**
     * brands
     *
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Nitsan\NitsanProduct\Domain\Model\Brand>
     * @TYPO3\CMS\Extbase\Annotation\ORM\Cascade("remove")
     */
    protected $brands = null;

    /**
     * __construct
     */
    public function __construct()
    {

        // Do not remove the next line: It would break the functionality
        $this->initializeObject();
    }

    /**
     * Initializes all ObjectStorage properties when model is reconstructed from DB (where __construct is not called)
     * Do not modify this method!
     * It will be rewritten on each save in the extension builder
     * You may modify the constructor of this class instead
     *
     * @return void
     */
    public function initializeObject()
    {
        $this->brands = $this->brands ?: new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
    }

    /**
     * Returns the name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Sets the name
     *
     * @param string $name
     * @return void
     */
    public function setName(string $name)
    {
        $this->name = $name;
    }

    /**
     * Returns the description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Sets the description
     *
     * @param string $description
     * @return void
     */
    public function setDescription(string $description)
    {
        $this->description = $description;
    }

    /**
     * Returns the image
     *
     * @return string
     */
    public function getImage(): ?\TYPO3\CMS\Extbase\Domain\Model\FileReference
    {
        return $this->image;
    }

    /**
     * Sets the image
     *
     * @param string $image
     * @return void
     */
    public function setImage(\TYPO3\CMS\Extbase\Domain\Model\FileReference $image)
    {
        $this->image = $image;
    }

    /**
     * Returns the price
     *
     * @return string
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Sets the price
     *
     * @param string $price
     * @return void
     */
    public function setPrice(string $price)
    {
        $this->price = $price;
    }

    /**
     * Adds a Brand
     *
     * @param \Nitsan\NitsanProduct\Domain\Model\Brand $brand
     * @return void
     */
    public function addBrand(\Nitsan\NitsanProduct\Domain\Model\Brand $brand)
    {
        $this->brands->attach($brand);
    }

    /**
     * Removes a Brand
     *
     * @param \Nitsan\NitsanProduct\Domain\Model\Brand $brandToRemove The Brand to be removed
     * @return void
     */
    public function removeBrand(\Nitsan\NitsanProduct\Domain\Model\Brand $brandToRemove)
    {
        $this->brands->detach($brandToRemove);
    }

    /**
     * Returns the brands
     *
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Nitsan\NitsanProduct\Domain\Model\Brand>
     */
    public function getBrands()
    {
        return $this->brands;
    }

    /**
     * Sets the brands
     *
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Nitsan\NitsanProduct\Domain\Model\Brand> $brands
     * @return void
     */
    public function setBrands(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $brands)
    {
        $this->brands = $brands;
    }
}
