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
 * @version   Release: 2.0.0
 * @since     Class available since Release 2.0.0
 */

namespace GoMage\Navigation\Model\Catalog\Layer\Filter;

use GoMage\Navigation\Model\Config\Source\NavigationInterface;
use GoMage\Navigation\Model\Catalog\Layer\Filter\FilterInterface;

/**
 * Class Category
 *
 * @package GoMage\Navigation\Model\Catalog\Layer\Filter
 */
class Category extends \Magento\CatalogSearch\Model\Layer\Filter\Category implements FilterInterface
{
    /**
     * @var CategoryDataProvider
     */
    protected $dataProvider;

    /**
     * @var \Magento\Catalog\Model\ResourceModel\Category
     */
    protected $categoryResource;

    /**
     * @var \Magento\Framework\Registry
     */
    protected $coreRegistry;

    /**
     * @var \Magento\Framework\App\RequestInterface
     */
    protected $request;

    /**
     * @var \GoMage\Navigation\Helper\Url
     */
    protected $urlHelper;

    /**
     * @var \GoMage\Navigation\Helper\Data
     */
    protected $helper;

    /**
     * @var string
     */
    protected $imageCat;

    /**
     * @var array
     */
    protected $imageCategories = [];

    /**
     * @var \Magento\Catalog\Model\Layer\Resolver
     */
    protected $layerResolver;

    /**
     * @var \Magento\Catalog\Model\ResourceModel\Category\CollectionFactory
     */
    protected $categoryCollectionFactory;

    /**
     * @var \GoMage\Navigation\Helper\CategoryData
     */
    protected $categoryHelper;

    /**
     * Category constructor.
     * @param \Magento\Catalog\Model\Layer\Filter\ItemFactory $filterItemFactory
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Catalog\Model\Layer $layer
     * @param \Magento\Catalog\Model\Layer\Filter\Item\DataBuilder $itemDataBuilder
     * @param \Magento\Framework\Escaper $escaper
     * @param \Magento\Catalog\Model\Layer\Resolver $layerResolver
     * @param \Magento\Catalog\Model\Layer\Filter\DataProvider\CategoryFactory $categoryDataProviderFactory
     * @param \Magento\Framework\Registry $coreRegistry
     * @param \Magento\Framework\App\RequestInterface $request
     * @param \GoMage\Navigation\Helper\Data $helper
     * @param \GoMage\Navigation\Helper\Url $urlHelper
     * @param \GoMage\Navigation\Helper\CategoryData $categoryHelper
     * @param \Magento\Catalog\Model\ResourceModel\Category\CollectionFactory $categoryCollectionFactory
     * @param \Magento\Catalog\Model\ResourceModel\Category $categoryResource
     * @param array $data
     */
    public function __construct(
        \Magento\Catalog\Model\Layer\Filter\ItemFactory $filterItemFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Catalog\Model\Layer $layer,
        \GoMage\Navigation\Model\Layer\Filter\Item\DataBuilder $itemDataBuilder,
        \Magento\Framework\Escaper $escaper,
        \Magento\Catalog\Model\Layer\Resolver $layerResolver,
        \Magento\Catalog\Model\Layer\Filter\DataProvider\CategoryFactory $categoryDataProviderFactory,
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Framework\App\RequestInterface $request,
        \GoMage\Navigation\Helper\Data $helper,
        \GoMage\Navigation\Helper\Url $urlHelper,
        \GoMage\Navigation\Helper\CategoryData $categoryHelper,
        \Magento\Catalog\Model\ResourceModel\Category\CollectionFactory $categoryCollectionFactory,
        \Magento\Catalog\Model\ResourceModel\Category $categoryResource,
        array $data = []
    ) {
        $this->categoryResource = $categoryResource;
        parent::__construct(
            $filterItemFactory,
            $storeManager,
            $layer,
            $itemDataBuilder,
            $escaper,
            $categoryDataProviderFactory,
            $data
        );
        $this->_requestVar = 'cat';
        $this->coreRegistry = $coreRegistry;
        $this->layerResolver = $layerResolver;
        $this->request = $request;
        $this->helper = $helper;
        $this->categoryHelper = $categoryHelper;
        $this->categoryCollectionFactory = $categoryCollectionFactory;
        $this->dataProvider = $categoryDataProviderFactory->create(['layer' => $this->getLayer()]);
        $this->urlHelper = $urlHelper;
    }

    /**
     * @param \Magento\Framework\App\RequestInterface $request
     * @return $this
     */
    public function apply(\Magento\Framework\App\RequestInterface $request)
    {
        if (!$this->helper->isEnable()) {
            return parent::apply($request);
        }

        if (empty($request->getParam($this->getRequestVar()))) {
            return parent::apply($request);
        }

        $filters = $this->getFormattedFilters();

        if (empty($filters)) {
            return parent::apply($request);
        }
        $this->getLayer()->getProductCollection()->addCategoriesFilter(['in' => $filters]);
        foreach ($filters as $filter) {
            $this->dataProvider->setCategoryId($filter);
            $category = $this->dataProvider->getCategory();
            $this->getLayer()->getState()->addFilter($this->_createItem($category->getName(), $filter));
        }
        $mainCategory = $this->coreRegistry->registry('current_category');
        if (!$mainCategory) {
            $mainCategory = $this->layerResolver->get()->getCurrentCategory();
        }
        $this->dataProvider->setCategoryId($mainCategory->getId());
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAttributeCode()
    {
        return NavigationInterface::ATTRIBUTE_CATEGORY;
    }

    /**
     * @return $this
     */
    public function getAttributeModel()
    {
        return $this;
    }
        /**
         * Get data array for building category filter items
         *
         * @return array
         */
        protected function _getItemsData()
    {
        /** @var \Magento\CatalogSearch\Model\ResourceModel\Fulltext\Collection $productCollection */
        $productCollection = $this->getLayer()->getProductCollection();
        $optionsFacetedData = $productCollection->getFacetedData('category');
        $category = $this->dataProvider->getCategory();
        $categories = $category->getChildrenCategories();

        if ($category->getIsActive()) {
            foreach ($categories as $category) {
                if ($category->getIsActive()
                    && isset($optionsFacetedData[$category->getId()])
                ) {
                    $this->categoryResource->load($category, $category->getId());
                    $this->imageCategories[$category->getId()] = $category->getImageUrl();
                    $this->imageCat[$category->getId()] = $category->getData('image');
                    $this->itemDataBuilder->addItemData(
                        $category->getName(),
                        $category->getId(),
                        $optionsFacetedData[$category->getId()]['count']
                    );
                }
            }
        }
        return $this->itemDataBuilder->build();
    }

    /**
     * @return bool
     */
    public function isCategoryFilter()
    {
        return true;
    }

    /**
     * @return string
     */
    public function getRemoveUrl()
    {
        return $this->urlHelper->getFilterRemoveUrl($this);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getImageFile($id)
    {
        return $this->imageCat[$id];
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getImageCategory($id) 
    {
        $id = (int) $id;
        return $this->imageCategories[$id];
    }

    /**
     * @return array
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function getFormattedFilters()
    {
        $filters = explode('_', $this->request->getParam($this->getRequestVar()));
        if (!$this->helper->isUseFriendlyUrls()) {
            return $filters;
        }

        $mainCategory = $this->coreRegistry->registry('current_category');
        if (!$mainCategory) {
            $mainCategory = $this->layerResolver->get()->getCurrentCategory();
        }

        $collection = $this->categoryCollectionFactory->create();
        $collection->addAttributeToSelect('name');
        $collection->addIsActiveFilter();
        $categoriesName = [];

        foreach ($collection as $category) {
            $parent = explode('/', $category->getPath());
            if ($this->request->getParam('parent_cat_'.$category->getId())) {
                $requestParent = $this->request->getParam('parent_cat_'.$category->getId());
            } else {
                $requestParent = $mainCategory->getId();
            }
            if (!in_array($requestParent, $parent)) {
                continue;
            }
            $categoriesName[$this->formatCategoryName($category->getName())] = $category->getId();
        }

        $params = [];

        foreach ($filters as $item) {
            $formatItem = $item;
            if (isset($categoriesName[$formatItem])) {
                $params[] = $categoriesName[$formatItem];
            }
        }

        return $params;
    }

    /**
     * @param $name
     * @return mixed|string
     */
    protected function formatCategoryName($name)
    {
        return mb_strtolower(urlencode($name));
    }

    /**
     * @return bool
     */
    public function isActive()
    {
        if ($this->request->getParam($this->_requestVar)) {
            return true;
        }

        return false;
    }

    /**
     * @return $this|\Magento\Catalog\Model\Layer\Filter\AbstractFilter
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function _initItems()
    {
        $data = $this->_getItemsData();
        $items = [];
        foreach ($data as $itemData) {
            $items[] = $this->_createItem(
                $itemData['label'],
                $itemData['value'],
                $itemData['count'],
                $itemData['parent_label'],
                $itemData['id'],
                $itemData['search_value']
            );
        }
        $this->_items = $items;
        return $this;
    }

    /**
     * @param string $label
     * @param mixed $value
     * @param int $count
     * @param null $categoryName
     * @param null $id
     * @param null $searchValue
     * @return \Magento\Catalog\Model\Layer\Filter\Item
     */
    protected function _createItem($label, $value, $count = 0, $categoryName = null, $id = null, $searchValue = null)
    {
        return $this->_filterItemFactory->create()
            ->setFilter($this)
            ->setLabel($label)
            ->setValue($value)
            ->setCatId($id)
            ->setCatName($categoryName)
            ->setSearchValue($searchValue)
            ->setCount($count);
    }
}
