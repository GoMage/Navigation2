<?php

namespace GoMage\Navigation\Model\Catalog\Layer\Filter;

use GoMage\Navigation\Model\Config\Source\NavigationInterface;

class Category extends \Magento\Catalog\Model\Layer\Filter\Category implements FilterInterface
{
    /**
     * @var CategoryDataProvider
     */
    private $dataProvider;

    /**
     * @var \Magento\Framework\Registry
     */
    private $coreRegistry;

    /**
     * @var \Magento\Framework\App\RequestInterface
     */
    protected $request;

    /**
     * @var \GoMage\Navigation\Helper\Data
     */
    protected $helper;

    /**
     * @var array
     */
    protected $imageCategories = [];

    /**
     * @var \Magento\Catalog\Model\ResourceModel\Category\CollectionFactory
     */
    protected $categoryCollectionFactory;

    /**
     * @var \GoMage\Navigation\Helper\CategoryData
     */
    protected $categoryHelper;

    public function __construct(
        \Magento\Catalog\Model\Layer\Filter\ItemFactory $filterItemFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Catalog\Model\Layer $layer,
        \Magento\Catalog\Model\Layer\Filter\Item\DataBuilder $itemDataBuilder,
        \Magento\Framework\Escaper $escaper,
        \Magento\Catalog\Model\Layer\Filter\DataProvider\CategoryFactory $categoryDataProviderFactory,
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Framework\App\RequestInterface $request,
        \GoMage\Navigation\Helper\Data $helper,
        \GoMage\Navigation\Helper\CategoryData $categoryHelper,
        \Magento\Catalog\Model\ResourceModel\Category\CollectionFactory $categoryCollectionFactory,
        array $data = []
    ) {
        parent::__construct($filterItemFactory, $storeManager, $layer, $itemDataBuilder, $escaper, $categoryDataProviderFactory, $data);
        $this->_requestVar = 'cat';
        $this->coreRegistry = $coreRegistry;
        $this->request = $request;
        $this->helper = $helper;
        $this->categoryHelper = $categoryHelper;
        $this->categoryCollectionFactory = $categoryCollectionFactory;
        $this->dataProvider = $categoryDataProviderFactory->create(['layer' => $this->getLayer()]);
    }

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
            $this->getLayer()->getProductCollection()->addCategoryFilter($category);
            $this->getLayer()->getState()->addFilter($this->_createItem($category->getName(), $filter));
        }

        $mainCategory = $this->coreRegistry->registry('current_category');
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
    public function _getItemsData()
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
    public function isCategoryFilter() {
        return true;
    }

    public function getImageFile($id)
    {
        return $this->imageCat[$id];
    }
    public function getImageCategory($id) {
        $id = (int) $id;
        return $this->imageCategories[$id];
    }
    protected function getFormattedFilters()
    {
        $filters = explode('_', $this->request->getParam($this->getRequestVar()));

        if (!$this->helper->isUseFriendlyUrls()) {
            return $filters;
        }

        $collection = $this->categoryCollectionFactory->create();
        $collection->addAttributeToSelect('name');
        $collection->addIsActiveFilter();

        $categoriesName = [];
        foreach ($collection as $category) {
            $categoriesName[html_entity_decode($this->formatCategoryName($category->getName()))] = $category->getId();
        }

        $params = [];
        foreach ($filters as $item) {

            if(isset($categoriesName[$item])) {
                $params[] = $categoriesName[$item];
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
        return mb_strtolower(str_replace(' ', '+', htmlentities($name)));
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
