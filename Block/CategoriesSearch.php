<?php
/**
 * Created by PhpStorm.
 * User: Димасик
 * Date: 10.06.2018
 * Time: 21:42
 */

namespace GoMage\Navigation\Block;

/**
 * Class CategoriesSearch
 *
 * @package GoMage\Navigation\Block
 */
class CategoriesSearch extends Categories
{
    /**
     * @var array
     */
    protected $facetsData = [];

    /**
     * @var \Magento\Framework\Registry $coreRegistry
     */
    protected $coreRegistry;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \GoMage\Navigation\Helper\CategoryHelper $categoryHelper,
        \Magento\Catalog\Model\Indexer\Category\Flat\State $categoryFlatState,
        \Magento\Theme\Block\Html\Topmenu $topMenu,
        \GoMage\Navigation\Helper\Data $dataHelper,
        \GoMage\Navigation\Helper\CategoryData $categoriesHelper,
        \GoMage\Navigation\Helper\NavigationViewData $navigationViewHelper,
        \GoMage\Navigation\Model\Config\Source\Category\Templates $templates,
        \Magento\Catalog\Model\ResourceModel\Category $categoryResource,
        \Magento\Catalog\Model\Layer\Resolver $layerResolver,
         \Magento\Framework\Registry $coreRegistry
    ) {
        $this->catalogLayer = $layerResolver->get();
        $this->categoryHelper = $categoryHelper;
        $this->categoryFlatConfig = $categoryFlatState;
        $this->topMenu = $topMenu;
        $this->dataHelper = $dataHelper;
        $this->categoriesHelper = $categoriesHelper;
        $this->templates = $templates;
        $this->categoryResource = $categoryResource;
        $this->navigationViewHelper = $navigationViewHelper;
        parent::__construct(
            $context,
            $categoryHelper,
            $categoryFlatState,
            $topMenu,
            $dataHelper,
            $categoriesHelper,
            $navigationViewHelper,
            $templates,
            $categoryResource,
            $layerResolver,
            $coreRegistry
        );
        $this->setLocation();
    }

    /**
     * @return $this|\Magento\Framework\View\Element\Template
     * @throws \Exception
     */
    protected function _beforeToHtml()
    {
        if (!$this->getDataHelper()->isEnable()
            || !$this->getCategoriesDataHelper()->isShowCategories() || !$this->canShowCategories) {
            return parent::_beforeToHtml();
        }
        if ($this->getCategoriesDataHelper()->isShowCategoryInShopBy()) {
            $templateFile = $this->templates
                ->getShowShopByInTemplateSearch($this->getCategoriesDataHelper()->getCategoriesNavigationType());
        } else {
            $templateFile = $this->templates
                ->getSearch($this->getCategoriesDataHelper()->getCategoriesNavigationType());
        }
        $this->setTemplate($templateFile);

        return $this;
    }

    /**
     * @param $category
     * @return bool
     */
    public function isHidecategory($category)
    {
        if(is_null($this->coreRegistry->registry('facets_categoties'))) {
            if(is_null($this->coreRegistry->registry('category_facets_without_filter'))) {
                return false;
            } else {
                $facetsData = $this->coreRegistry->registry('category_facets_without_filter');
                $this->coreRegistry->register('facets_categoties', $facetsData);
            }
        }
        return $this->getCategoriesDataHelper()->isHideEmptyCategories() &&
            !$this->getProductsCount($category);
    }

}
