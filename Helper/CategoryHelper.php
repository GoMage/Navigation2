<?php

/**
 * GoMage.com
 *
 * GoMage Navigation M2
 *
 * @category  Extension
 * @copyright Copyright (c) 2018-2018 GoMage.com (https://www.gomage.com)
 * @author    GoMage.com
 * @license   https://www.gomage.com/licensing  Single domain license
 * @terms     of use https://www.gomage.com/terms-of-use
 * @version   Release: 1.0.0
 * @since     Class available since Release 1.0.0
 */

namespace GoMage\Navigation\Helper;

use Magento\Catalog\Api\CategoryRepositoryInterface;
use Magento\Framework\Registry;

/**
 * Class CategoryHelper
 *
 * @package GoMage\Navigation\Helper
 */
class CategoryHelper extends \Magento\Catalog\Helper\Category
{
    /**
     * @var Registry $registry
     */
    private $registry;

    /**
     * @var \Magento\Catalog\Model\CategoryFactory $categoryFactory
     */
    private $categoryFactory;

    /**
     * @var \Magento\Catalog\Model\ResourceModel\Category\CollectionFactory
     */
    protected $categoryCollectionFactory;

    /**
     * @param \Magento\Framework\App\Helper\Context                           $context
     * @param \Magento\Catalog\Model\CategoryFactory                          $categoryFactory
     * @param \Magento\Store\Model\StoreManagerInterface                      $storeManager
     * @param \Magento\Framework\Data\CollectionFactory                       $dataCollectionFactory
     * @param Registry                                                        $registry
     * @param CategoryRepositoryInterface                                     $categoryRepository
     * @param \Magento\Catalog\Model\ResourceModel\Category\CollectionFactory $categoryCollectionFactory
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Catalog\Model\CategoryFactory $categoryFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\Data\CollectionFactory $dataCollectionFactory,
        Registry $registry,
        CategoryRepositoryInterface $categoryRepository,
        \Magento\Catalog\Model\ResourceModel\Category\CollectionFactory $categoryCollectionFactory
    ) {
        $this->categoryFactory = $categoryFactory;
        $this->categoryCollectionFactory = $categoryCollectionFactory;
        $this->registry = $registry;
        parent::__construct($context, $categoryFactory, $storeManager, $dataCollectionFactory, $categoryRepository);
    }

    public function getStoreCategories($sorted = false, $asCollection = false, $toLoad = true)
    {
        $parent = $this->registry->registry('current_category');
        if (!$parent) {
            $parent = $this->_storeManager->getStore()->getRootCategoryId();
        } else {
            $parent = $parent->getId();
        }
        $cacheKey = sprintf('%d-%d-%d-%d', $parent, $sorted, $asCollection, $toLoad);
        if (isset($this->_storeCategories[$cacheKey])) {
            return $this->_storeCategories[$cacheKey];
        }

        /**
         * Check if parent node of the store still exists
         */
        $category = $this->_categoryFactory->create();
        if (!$category->checkId($parent)) {
            if ($asCollection) {
                return $this->_dataCollectionFactory->create();
            }
            return [];
        }

        $recursionLevel = max(
            0,
            (int)$this->scopeConfig->getValue(
                'catalog/navigation/max_depth',
                \Magento\Store\Model\ScopeInterface::SCOPE_STORE
            )
        );
        $storeCategories = $category->getCategories($parent, $recursionLevel, $sorted, $asCollection, $toLoad);
        $this->_storeCategories[$cacheKey] = $storeCategories;
        return $storeCategories;
    }

    public function getCategoryCollection()
    {
        return $this->categoryCollectionFactory;
    }

    public function getCategoryFactory()
    {
        return $this->categoryFactory;
    }
}
