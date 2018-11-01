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

namespace GoMage\Navigation\Model\Catalog\Layer\Search;

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
    protected $categoryCatalogHelper;

    protected $catInfo;
    /**
     * @var \Magento\Catalog\Model\ResourceModel\Category\CollectionFactory
     */
    protected $categoryCollectionFactory;

    /**
     * @var \GoMage\Navigation\Helper\CategoryData
     */
    protected $categoryHelper;

    protected $valueSearch;

    /**
     * @var \Magento\Framework\Escaper
     */
    private $escaper;
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
        \GoMage\Navigation\Helper\Catalog\Category $categoryCatalogHelper,
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
        $this->categoryCatalogHelper = $categoryCatalogHelper;
        $this->escaper = $escaper;
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
        $productCollection  =  $this->getLayer()->getProductCollection();
        $productCollectionFacets = clone $this->getLayer()->getProductCollection();
        $facetsData = $productCollectionFacets->addCategoryFilter($this->layerResolver->get()->getCurrentCategory())
            ->getFacetedData('category');
        $this->coreRegistry->register('category_facets_without_filter', $facetsData);
        $productCollection->addCategoriesFilterSearch($filters);
        foreach ($filters as $filter) {
            $filterCode = isset($this->valueSearch[$filter]) ? $this->valueSearch[$filter] : null;
            $this->dataProvider->setCategoryId($filter);
            $category = $this->dataProvider->getCategory();
            $this->getLayer()->getState()->addFilter($this->_createItem(isset($this->catInfo[$filter])
                ? $this->catInfo[$filter] :$category->getName(),
                $filter, 0, null, null,
                $this->reFormatCategoryName($filterCode)));
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
     * @return array
     * @throws \Magento\Framework\Exception\StateException
     */
    protected function _getItemsData()
    {
        /** @var \Magento\CatalogSearch\Model\ResourceModel\Fulltext\Collection $productCollection */
        $productCollection = $this->getLayer()->getProductCollection();
        $optionsFacetedData = $this->coreRegistry->registry('category_facets_without_filter')
            ?: $this->getLayer()->getProductCollection()->getFacetedData('category');
        $categoryRoot = $this->dataProvider->getCategory();
        $categoryCollection = $this->categoryCatalogHelper->getStoreCategories('entity_id', true, false);
        $categoryCollection->addAttributeToSelect('name')
            ->addAttributeToSelect('image');
        $items = $categoryCollection->getItems();
        if ($categoryRoot->getIsActive()) {
            foreach ($items as $key=>$category) {
                $path = explode('/', $category->getPath());
                $namePath = '';
                foreach ($path as $item) {
                    $idCat = (int)$item;
                    if(isset($items[$idCat])) {
                        $cat = $items[$idCat];
                        $namePath.='/'.$cat->getName();
                    }
                }

                if(!$namePath) {
                    $namePath = $category->getName();
                }
                $namePath = ltrim($namePath, '/');
                $categoryLabel = str_replace('/','-', $namePath);
                if ($category->getIsActive()
                    && isset($optionsFacetedData[$category->getId()])
                ) {
                    $this->imageCategories[$category->getId()] = $category->getImageUrl();
                    $this->imageCat[$category->getId()] = $category->getData('image');
                    $this->itemDataBuilder->addItemData(
                        $categoryLabel,
                        $category->getId(),
                        $optionsFacetedData[$category->getId()]['count'],
                        $namePath,
                        $category->getId(),
                        $categoryLabel
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
        $names = [];
        foreach ($filters as $filter) {
            $items = explode('-', $filter);
            if(!$items) {
                $items[] = $filter;
            }
            foreach ($items as $item)
                $names[] = $this->reFormatCategoryName($item);
        }
        $collection->addAttributeToFilter('name', ['in'=>[$names]]);
        $collection->addIsActiveFilter();
        $items = $collection->getItems();
        $catInfo = [];
        $catName = [];
        $valueSearch = [];
        foreach ($filters as $filter) {
            $filterPath = explode('-', $filter);
            $lastChild = $filterPath[count($filterPath) - 1];
            $rootPath = $this->reFormatCategoryName($filterPath[0]);
            $lastChild = $this->reFormatCategoryName($lastChild);
            $pathEnd = [];
            $strName = '';
            $nameEnd = '';
            foreach ($items as $item) {
                if (count($filterPath) === 1) {
                    $formatedLabelSingleCategory = $this->reFormatCategoryName($filterPath[0]);
                    if ($formatedLabelSingleCategory === mb_strtolower($item->getName())) {
                        $catInfo[$filter] = $item->getId();
                    }
                    continue;
                }
                if ($lastChild === mb_strtolower($item->getName())) {
                    $pathEnd[$item->getId()] = $item->getPath();
                    if(count($pathEnd) === 1) {
                        $nameEnd = $item->getName();
                    }
                    continue;
                }
                if (in_array(mb_strtolower($this->formatCategoryName($item->getName())), $filterPath)) {
                    if (!isset($catInfo[$filter]) && mb_strtolower($item->getName()) === $rootPath) {
                        $strName .= $item->getName();
                        $catInfo[$filter][] = $item->getId();
                    } else if (isset($catInfo[$filter])) {
                        $arrChild = explode('/', $item->getPath());
                        $result = array_intersect($catInfo[$filter], $arrChild);
                        if (count($result) === count($catInfo[$filter])) {
                            $strName .='/'.$item->getName();
                            $catInfo[$filter][] = $item->getId();
                        }
                    }
                }
            }
            if ($pathEnd) {
                foreach ($pathEnd as $key => $path) {
                    $pathInfoArray = explode('/', $path);
                    if (!isset($catInfo[$filter]) || !is_array($catInfo[$filter])) {
                        continue;
                    }
                    $result = array_intersect($catInfo[$filter], $pathInfoArray);
                    if (count($result) === count($catInfo[$filter])) {
                        $strName .='/'.$nameEnd;
                        $catInfo[$filter] = $key;
                        $catName[$key] = $strName;
                        $valueSearch[$key] = $filter;
                    }
                }
            }
        }
        $this->valueSearch = $valueSearch;
        $this->catInfo = $catName;
        $params = [];
        foreach ($catInfo as $key => $item) {
            $params[] = $item;
        }

        return $params;
    }

    /**
     * @param $name
     * @return mixed|string
     */
    protected function formatCategoryName($name)
    {
        return urlencode(mb_strtolower($name));
    }

    protected function reFormatCategoryName($name)
    {
        return urldecode(mb_strtolower($name));
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
