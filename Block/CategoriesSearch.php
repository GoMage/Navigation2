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
    protected $facetsData=[];

    /**
     * @var \Magento\Framework\Registry $coreRegistry
     */
    protected $coreRegistry;

    /**
     * @param \Magento\Framework\View\Element\Template\Context          $context
     * @param \GoMage\Navigation\Helper\CategoryHelper                  $categoryHelper
     * @param \Magento\Catalog\Model\Indexer\Category\Flat\State        $categoryFlatState
     * @param \Magento\Theme\Block\Html\Topmenu                         $topMenu
     * @param \GoMage\Navigation\Helper\Data                            $dataHelper
     * @param \GoMage\Navigation\Helper\CategoryData                    $categoriesHelper
     * @param \GoMage\Navigation\Helper\NavigationViewData              $navigationViewHelper
     * @param \GoMage\Navigation\Model\Config\Source\Category\Templates $templates
     * @param \Magento\Catalog\Model\ResourceModel\Category             $categoryResource
     * @param \Magento\Catalog\Model\Layer\Resolver                     $layerResolver
     */
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
        $this->coreRegistry = $coreRegistry;
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
            $layerResolver
        );
        $this->setLocation();
    }

    /**
     * @return $this
     */
    protected function _beforeToHtml()
    {
        if (!$this->getDataHelper()->isEnable() || !$this->getCategoriesDataHelper()->isShowCategories() || !$this->canShowCategories) {
            return parent::_beforeToHtml();
        }
        if ($this->getCategoriesDataHelper()->isShowCategoryInShopBy()) {
            $templateFile = $this->templates->getShowShopByInTemplateSearch($this->getCategoriesDataHelper()->getCategoriesNavigationType());
        } else {
            $templateFile = $this->templates->getSearch($this->getCategoriesDataHelper()->getCategoriesNavigationType());
        }
        $this->setTemplate($templateFile);

        return $this;
    }

    public function getFacetsData()
    {
        if(!$this->facetsData) {
            if(!$this->coreRegistry->registry('facets_collection')) {
                $productCollection = $this->catalogLayer->getProductCollection();
            } else {
                $productCollection = $this->coreRegistry->registry('facets_collection');
            }
            $facedsData = $productCollection->getFacetedData('category');
            $this->facetsData = $facedsData;
        }
        return $this->facetsData;

    }

    /**
     * @return mixed
     */
    public function getCurrentCategoryId()
    {
        return $this->catalogLayer->getCurrentCategory()->getId();
    }

    public function getChildCategoriesObject($category)
    {
        if ($this->categoryFlatConfig->isFlatEnabled() && $category->getUseFlatResource()) {
            return $subcategories = (array)$category->getChildrenNodes();
        } else {
            return $subcategories = $category->getChildren();
        }
    }

    public function getCategoriesSearch($category)
    {
        $this->getFacetsData();
        if(!$this->facetsData || !$this->getChildCategoriesObject($category)) {
            $productCollection = $this->catalogLayer->getProductCollection();
            $this->facetsData = $productCollection->getFacetedData('category');
            return [];
        } else {
            $arrCat = [];
            $categories = $this->getChildCategoriesObject($category);
            foreach ($categories as $cat) {
                if((isset($this->facetsData[$category->getId()])) && $this->facetsData[$category->getId()]['count'] > 0) {
                    $arrCat[$category->getId()] = $category->setParentId($category->getId());
                } else {
                    $arrCatTmp = $this->getCategoriesSearch($cat);
                    if($arrCatTmp) {
                        $arrCat = $arrCat + $arrCatTmp;
                    }
                }

            }

            return $arrCat;
        }
    }

    /**
     * @param $data
     * @return string
     */
    public function getOlList($data)
    {
        $block = $this->getListBlock();
        $block->setTemplate('GoMage_Navigation::categories/list/search/ol_list.phtml');
        $block->assign('data', $data);

        return $block->toHtml();
    }

    /**
     * @param $data
     * @return string
     */
    public function getOlImageList($data)
    {
        $block = $this->getListBlock();
        $block->setTemplate('GoMage_Navigation::categories/list/search/image.phtml');
        $block->assign('data', $data);

        return $block->toHtml();
    }

    /**
     * @param $data
     * @return string
     */
    public function getSelectList($data)
    {
        $block = $this->getListBlock();
        $block->setTemplate('GoMage_Navigation::categories/list/search/select.phtml');
        $block->assign('data', $data);

        return $block->toHtml();
    }

    /**
     * @param $data
     * @return string
     */
    public function getImageCategoriesList($data)
    {
        $block = $this->getListBlock();
        $block->setTemplate('GoMage_Navigation::categories/list/search/image.phtml');
        $block->assign(
            'alignment',
            ($this->getCategoriesDataHelper()->getCategoriesImageAlignment()) ? 'alignment:' . $this->getCategoriesDataHelper()->getCategoriesImageAlignment() : ''
        );
        $block->assign(
            'width',
            ($this->getCategoriesDataHelper()->getCategoriesImageWidth()) ? $this->getCategoriesDataHelper()->getCategoriesImageWidth() : ''
        );
        $block->assign(
            'height',
            ($this->getCategoriesDataHelper()->getCategoriesImageHeight()) ? $this->getCategoriesDataHelper()->getCategoriesImageHeight() : ''
        );
        $block->assign('data', $data);

        return $block->toHtml();
    }
}
