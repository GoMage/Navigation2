<?php

namespace GoMage\Navigation\Block;

class CategoriesCms extends Categories
{
    /**
     * @var \Magento\Catalog\Helper\Category
     */
    protected $categoryHelper;

    /**
     * @var bool
     */
    protected $image = false;

    /**
     * @var
     */
    protected $categoryParent;

    /**
     * @var \Magento\Cms\Model\Page
     */
    protected $cmsPage;

    /**
     * @var \Magento\Catalog\Model\Indexer\Category\Flat\State
     */
    protected $categoryFlatConfig;

    /**
     * @var \Magento\Theme\Block\Html\Topmenu
     */
    protected $topMenu;

    /**
     * @var \GoMage\Navigation\Helper\Data
     */
    protected $dataHelper;

    /**
     * @var \GoMage\Navigation\Helper\CategoryData
     */
    protected $categoriesHelper;

    /**
     * @var \GoMage\Navigation\Model\Config\Source\Category\Templates
     */
    protected $templates;

    /**
     * @var \Magento\Catalog\Model\ResourceModel\Category
     */
    protected $categoryResource;

    /**
     * @var string
     */
    protected $pageLayout;

    /**
     * @var bool
     */
    protected $canShowCategories;

    /**
     * @var \Magento\Catalog\Model\Layer
     */
    protected $catalogLayer;

    /**
     * @var \GoMage\Navigation\Helper\NavigationViewData
     */
    protected $navigationViewHelper;
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
        \Magento\Framework\Registry $registry
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
        $this->cmsPage = $registry->registry('gomage_cms_page');
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
            $registry
        );
        $this->setLocation();
    }

    /**
     * Set block location according settings
     */
    protected function setLocation()
    {
        if (!$this->getDataHelper()->isEnable() ||  $this->getCategoriesDataHelper()->isShowCategoryInShopBy()) {
            if($this->getLayout()->isBlock('gomage.categories')) {
                $this->getLayout()->unsetElement('gomage.categories');
            } elseif ($this->getLayout()->isBlock('gomage.categories.column')) {
                $this->getLayout()->unsetElement('gomage.categories.column');
            }
            return;
        }
        if ($this->cmsPage->getLocation() == \GoMage\Navigation\Model\Config\Source\Place::CONTENT
            && $this->cmsPage->getPageLayout() == '1column'
        ) {
            $this->moveColumn('main');
            $this->canShowCategories = true;
            return;
        } else {
            $this->getLayout()->unsetChild('main', 'gomage.categories.column');
        }

        if ($this->cmsPage->getLocation() == \GoMage\Navigation\Model\Config\Source\Place::LEFT_COLUMN
            && $this->cmsPage->getPageLayout() == '2columns-left'
        ) {
            $this->moveBlock('sidebar.main');
            $this->canShowCategories = true;
            return;
        }
        if ($this->cmsPage->getLocation() == \GoMage\Navigation\Model\Config\Source\Place::CONTENT
            && $this->cmsPage->getPageLayout() == '2columns-left'
        ) {
            $this->moveBlock('main');
            $this->getLayout()->reorderChild('main', 'gomage.categories', 0);
            $this->canShowCategories = true;
            return;
        }

        if ($this->cmsPage->getLocation() == \GoMage\Navigation\Model\Config\Source\Place::LEFT_COLUMN
            && $this->cmsPage->getPageLayout() == '3columns'
        ) {
            $this->moveBlock('sidebar.main');
            $this->canShowCategories = true;
            return;
        }

        if ($this->cmsPage->getLocation() == \GoMage\Navigation\Model\Config\Source\Place::RIGHT_COLUMN
            && $this->cmsPage->getPageLayout() == '2columns-right'
        ) {
            $this->moveBlock('sidebar.main');
            $this->canShowCategories = true;
            return;
        }

        if ($this->cmsPage->getLocation() == \GoMage\Navigation\Model\Config\Source\Place::CONTENT
            && $this->cmsPage->getPageLayout() == '2columns-right'
        ) {
            $this->moveBlock('main');
            $this->getLayout()->reorderChild('main', 'gomage.categories', 0);
            $this->canShowCategories = true;
            return;
        }

        if ($this->cmsPage->getLocation() == \GoMage\Navigation\Model\Config\Source\Place::RIGHT_COLUMN
            && $this->cmsPage->getPageLayout() == '3columns'
        ) {
            $this->moveBlock('sidebar.additional');
            $this->canShowCategories = true;
            return;
        }

        if ($this->cmsPage->getLocation() == \GoMage\Navigation\Model\Config\Source\Place::CONTENT
            && $this->cmsPage->getPageLayout() == '3columns'
        ) {
            $this->moveBlock('main');
            $this->canShowCategories = true;
            return;
        }
    }

    /**
     * @param $parent
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function moveBlock($parent)
    {
        $this->getLayout()->unsetChild('sidebar.main', 'gomage.categories');
        $this->getLayout()->setChild($parent, 'gomage.categories', 'gomage.categories.moved');
        $this->getLayout()->reorderChild($parent, 'gomage.categories', 0);
    }

}
