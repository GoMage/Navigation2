<?php

namespace GoMage\Navigation\Helper;
use Magento\Catalog\Api\CategoryRepositoryInterface;
use Magento\Framework\Registry;

class CategoryHelper extends \Magento\Catalog\Helper\Category
{
    private $registry;
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Catalog\Model\CategoryFactory $categoryFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\Data\CollectionFactory $dataCollectionFactory,
        Registry $registry,
        CategoryRepositoryInterface $categoryRepository

    ) {
        $this->registry = $registry;
        parent::__construct($context, $categoryFactory, $storeManager, $dataCollectionFactory, $categoryRepository);
    }

    public function getStoreCategories($sorted = false, $asCollection = false, $toLoad = true)
    {
        $parent = $this->registry->registry('current_category');
        if(!$parent) {
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
        /* @var $category ModelCategory */
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
}