<?php
/**
 * Created by PhpStorm.
 * User: Димасик
 * Date: 07.06.2018
 * Time: 06:41
 */

namespace GoMage\Navigation\Model\Catalog\Layer\Search;


/**
 * Class CategorySearch
 *
 * @package GoMage\Navigation\Model\Catalog\Layer\Filter
 */
class CategorySearch extends Category
{
    /**
     * @var CategoryDataProvider
     */
    protected $dataProvider;

    protected $categoryHelperCore;

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

    protected $categoryFlatState;

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
     * @param \Magento\Catalog\Model\Layer\Filter\ItemFactory                  $filterItemFactory
     * @param \Magento\Store\Model\StoreManagerInterface                       $storeManager
     * @param \Magento\Catalog\Model\Layer                                     $layer
     * @param \Magento\Catalog\Model\Layer\Filter\Item\DataBuilder             $itemDataBuilder
     * @param \Magento\Framework\Escaper                                       $escaper
     * @param \Magento\Catalog\Model\Layer\Resolver                            $layerResolver
     * @param \Magento\Catalog\Model\Layer\Filter\DataProvider\CategoryFactory $categoryDataProviderFactory
     * @param \Magento\Framework\Registry                                      $coreRegistry
     * @param \Magento\Framework\App\RequestInterface                          $request
     * @param \GoMage\Navigation\Helper\Data                                   $helper
     * @param \GoMage\Navigation\Helper\Url                                    $urlHelper
     * @param \GoMage\Navigation\Helper\CategoryData                           $categoryHelper
     * @param \GoMage\Navigation\Helper\CategoryHelper                         $categoryHelperCore
     * @param \Magento\Catalog\Model\ResourceModel\Category\CollectionFactory  $categoryCollectionFactory
     * @param \Magento\Catalog\Model\Indexer\Category\Flat\State               $categoryFlatState
     * @param array                                                            $data
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
        \GoMage\Navigation\Helper\CategoryHelper $categoryHelperCore,
        \Magento\Catalog\Model\ResourceModel\Category\CollectionFactory $categoryCollectionFactory,
        \Magento\Catalog\Model\Indexer\Category\Flat\State $categoryFlatState,
        \Magento\Catalog\Model\ResourceModel\Category $categoryResource,
        array $data = []
    ) {
        parent::__construct(
            $filterItemFactory,
            $storeManager,
            $layer,
            $itemDataBuilder,
            $escaper,
            $layerResolver,
            $categoryDataProviderFactory,
            $coreRegistry,
            $request,
            $helper,
            $urlHelper,
            $categoryHelper,
            $categoryCollectionFactory,
            $categoryResource,
            $data
        );
        $this->_requestVar = 'cat';
        $this->categoryFlatState = $categoryFlatState;
        $this->coreRegistry = $coreRegistry;
        $this->layerResolver = $layerResolver;
        $this->request = $request;
        $this->helper = $helper;
        $this->categoryHelperCore = $categoryHelperCore;
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
        $categoryForFacetsData = clone $this->getLayer()->getProductCollection();
        $this->coreRegistry->register('facets_collection', $categoryForFacetsData);
        $this->getLayer()->getProductCollection()->addCategoriesFilter(['in' => $filters]);
        foreach ($filters as $filter) {
            $this->dataProvider->setCategoryId($filter);
            $category = $this->dataProvider->getCategory();
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
     * @return \Magento\Framework\Data\Tree\Node\Collection
     */
    public function getStoreCategories()
    {
        return $this->categoryHelperCore->getStoreCategories(true, false, true);
    }

    /**
     * @return array
     */
    protected function _getItemsData()
    {
        $productCollection = $this->getLayer()->getProductCollection();
        $optionsFacetedData = $productCollection->getFacetedData('category');

            $categories = $this->getStoreCategories();
        foreach ($categories as $category) {
            foreach ( $this->getCategoriesSearch($category, $optionsFacetedData) as $categoriesSearch) {
                if ($categoriesSearch->getIsActive() && ($this->helper->isShowEmptyCategory())) {
                    $this->imageCategories[$categoriesSearch->getId()] = $categoriesSearch->getImageUrl();
                    $this->imageCat[$categoriesSearch->getId()] = $category->getData('image');
                    $this->itemDataBuilder->addItemData(
                        $category->getName(),
                        $category->getId(),
                        $optionsFacetedData[$categoriesSearch->getId()]['count']
                    );
                }
            }
        }
        return $this->itemDataBuilder->build();
    }

    /**
     * @param $category
     * @return array
     */
    public function getChildCategoriesObject($category)
    {
        if ($this->categoryFlatState->isFlatEnabled() && $category->getUseFlatResource()) {
            $subcategories = (array)$category->getChildrenNodes();
            if(!$subcategories) {
                return [];
            }
            return $subcategories;
        } else {
             $subcategories = $category->getChildren();
            if(!$subcategories) {
                return [];
            }
             return $subcategories;
        }
    }

    /**
     * @param $category
     * @param $facetsCategory
     * @return array
     */
    public function getCategoriesSearch($category, $facetsCategory)
    {
            $arrCat = [];
            $categories = $this->getChildCategoriesObject($category);
        foreach ($categories as $cat) {
            if((isset($this->facetsData[$category->getId()])) && $facetsCategory[$category->getId()]['count'] > 0) {
                $arrCat[$category->getId()] = $category->setParentId($category->getId());
            } else {
                $arrCatTmp = $this->getCategoriesSearch($cat, $facetsCategory);
                if($arrCatTmp) {
                    $arrCat = $arrCat + $arrCatTmp;
                }
            }

        }
            return $arrCat;
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
        $collection = $this->categoryCollectionFactory->create();
        $collection->addAttributeToSelect('name');
        $collection->addIsActiveFilter();
        $categoriesName = [];
        foreach ($collection as $category) {
            $parent = explode('/', $category->getPath());
            if($this->request->getParam('parent_cat_'.$category->getId())) {
                $requestParent = $this->request->getParam('parent_cat_'.$category->getId());
            } else {
                $requestParent = false;
            }
            if(!$requestParent && ($requestParent != $category->getParentCategory()->getId() || $requestParent!=$category)) {
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
}
