<?php

namespace GoMage\Navigation\Model\Catalog\Layer\Filter;

use GoMage\Navigation\Model\Config\Source\NavigationInterface;

/**
 * Class Category
 * @package GoMage\Navigation\Model\Catalog\Layer\Filter
 */
class Category extends \Magento\Catalog\Model\Layer\Filter\Category implements FilterInterface
{
    /**
     * @var CategoryDataProvider
     */
    protected $dataProvider;

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
     * @param array $data
     */
    public function __construct(
        \Magento\Catalog\Model\Layer\Filter\ItemFactory $filterItemFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Catalog\Model\Layer $layer,
        \Magento\Catalog\Model\Layer\Filter\Item\DataBuilder $itemDataBuilder,
        \Magento\Framework\Escaper $escaper,
        \Magento\Catalog\Model\Layer\Resolver $layerResolver,
        \Magento\Catalog\Model\Layer\Filter\DataProvider\CategoryFactory $categoryDataProviderFactory,
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Framework\App\RequestInterface $request,
        \GoMage\Navigation\Helper\Data $helper,
        \GoMage\Navigation\Helper\Url $urlHelper,
        \GoMage\Navigation\Helper\CategoryData $categoryHelper,
        \Magento\Catalog\Model\ResourceModel\Category\CollectionFactory $categoryCollectionFactory,
        array $data = []
    ) {
        parent::__construct($filterItemFactory, $storeManager, $layer, $itemDataBuilder, $escaper, $categoryDataProviderFactory, $data);
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

        if(empty($request->getParam($this->getRequestVar()))) {
            return parent::apply($request);
        }

        $filters = $this->getFormattedFilters();

        if(empty($filters)) {
            return parent::apply($request);
        }
        $this->getLayer()->getProductCollection()->addCategoriesFilter(['in' => $filters]);
        foreach ($filters as $filter) {

            $this->dataProvider->setCategoryId($filter);
            $category = $this->dataProvider->getCategory();
            //$this->getLayer()->getProductCollection()->addCategoryFilter($category);
            $this->getLayer()->getState()->addFilter($this->_createItem($category->getName(), $filter));
        }
        $mainCategory = $this->coreRegistry->registry('current_category');
        if(!$mainCategory) {
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
     */
    protected function _getItemsData()
    {
        if(!$this->categoryHelper->isShowCategoryInShopBy()) {
            return [];
        }

        $productCollection = $this->getLayer()->getProductCollection();
        $optionsFacetedData = $productCollection->getFacetedData('category');
        $category = $this->coreRegistry->registry('current_category');
        if( $category ) {
            $categories = $category->getChildrenCategories();
        } else  {
            $category = $this->dataProvider->getCategory();
            $categories = $category->getChildrenCategories();
        }
        $i = 0;
        foreach ($categories as $category) {

            $count = (!empty($optionsFacetedData[$category->getId()]['count'])) ? $optionsFacetedData[$category->getId()]['count'] : 0;
            if ($category->getIsActive() && ($count > 0 || $this->helper->isShowEmptyCategory()) )
            {
                $category->load($category->getId());
                $this->imageCategories[$category->getId()] = $category->getImageUrl();
                $this->imageCat[$category->getId()] = $category->getData('image');
                $this->itemDataBuilder->addItemData(
                    $category->getName(),
                    $category->getId(),
                    $count
                );
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
    public function getImageCategory($id) {
        $id = (int) $id;
        return $this->imageCategories[$id];
    }

    /**
     * @return array
     */
    protected function getFormattedFilters()
    {
        $filters = explode('_', $this->request->getParam($this->getRequestVar()));
        if (!$this->helper->isUseFriendlyUrls()) {
            return $filters;
        }

        $mainCategory = $this->coreRegistry->registry('current_category');
        if(!$mainCategory) {
            $mainCategory = $this->layerResolver->get()->getCurrentCategory();
        }

        $collection = $this->categoryCollectionFactory->create();
        $collection->addAttributeToSelect('name');
        $collection->addIsActiveFilter();
        $categoriesName = [];

        foreach ($collection as $category) {
            $parent = explode('/', $category->getPath());
            if($this->request->getParam('parent_cat_'.$category->getId())) {
                $requestParent = $this->request->getParam('parent_cat_'.$category->getId());
            } else {
                $requestParent = $mainCategory->getId();
            }
            if(!in_array($requestParent ,$parent)) {
                continue;
            }
            $categoriesName[html_entity_decode($this->formatCategoryName($category->getName()))] = $category->getId();
        }

        $params = [];

        foreach ($filters as $item) {
            $formatItem = $this->formatCategoryName($item);
            if(isset($categoriesName[$formatItem])) {
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
        return mb_strtolower(str_replace(' ', '+', $name));
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
}
