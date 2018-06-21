<?php

namespace GoMage\Navigation\Block;

class Categories extends \Magento\Framework\View\Element\Template
{
    /**
     * @var \GoMage\Navigation\Helper\CategoryHelper
     */
    protected $categoryHelper;

    protected $image = false;

    protected $categoryParent;

    protected $categoryFactory;

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

    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \GoMage\Navigation\Helper\CategoryHelper $categoryHelper
     * @param \Magento\Catalog\Model\Indexer\Category\Flat\State $categoryFlatState
     * @param \Magento\Theme\Block\Html\Topmenu $topMenu
     * @param \GoMage\Navigation\Helper\Data $dataHelper
     * @param \GoMage\Navigation\Helper\CategoryData $categoriesHelper
     * @param \GoMage\Navigation\Helper\NavigationViewData $navigationViewHelper
     * @param \GoMage\Navigation\Model\Config\Source\Category\Templates $templates
     * @param \Magento\Catalog\Model\ResourceModel\Category $categoryResource
     * @param \Magento\Catalog\Model\Layer\Resolver $layerResolver
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
        \Magento\Catalog\Model\Layer\Resolver $layerResolver
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
        parent::__construct($context);
        $this->setLocation();
    }

    /**
     * @return mixed
     */
    protected function getPageLayout()
    {
        if (empty($this->pageLayout)) {
            $this->pageLayout = $this->catalogLayer->getCurrentCategory()->getPageLayout();
        }

        if (empty($this->pageLayout)) {
            $this->pageLayout = $this->getLayout()->getUpdate()->getPageLayout();
        }

        return $this->pageLayout;
    }

    /**
     * @return \GoMage\Navigation\Helper\CategoryData
     */
    public function getCategoriesDataHelper()
    {
        return $this->categoriesHelper;
    }

    /**
     * @return \GoMage\Navigation\Helper\Data
     */
    public function getDataHelper()
    {
        return $this->dataHelper;
    }

    /**
     * @return \GoMage\Navigation\Helper\CategoryHelper
     */
    public function getCategoryHelper()
    {
        return $this->categoryHelper;
    }

    /**
     * @return \GoMage\Navigation\Helper\NavigationViewData
     */
    public function getNavigationViewHelper()
    {
        return $this->navigationViewHelper;
    }

    public function setCategoryParent($category)
    {
        $this->categoryParent = $category->getName();
    }

    public function getCategoryParent($category)
    {
       return $this->categoryParent;
    }

    /**
     * @return mixed
     */
    public function getCurrentCategoryId()
    {
        return $this->catalogLayer->getCurrentCategory()->getId();
    }

    /**
     * @return \Magento\Framework\Data\Tree\Node\Collection
     */
    public function getStoreCategories()
    {
        return $this->getCategoryHelper()->getStoreCategories(true, false, true);
    }

    /**
     * @param $category
     * @return array
     */
    public function getChildCategories($category)
    {
        $data = [];
        if ($this->categoryFlatConfig->isFlatEnabled() && $category->getUseFlatResource()) {
            $subcategories = (array)$category->getChildrenNodes();
        } else {
            $subcategories = $category->getChildren();
        }
        if($subcategories) {
            foreach ($subcategories as $cat) {
                if ($this->getCategoriesDataHelper()->isHideEmptyCategories() && !$this->getProductsCount($cat) && !$cat->getChildrenCount()) {
                    continue;
                }

                $data[] = [
                    'entity_id' => $cat['entity_id'],
                    'url' => $this->getCategoryHelper()->getCategoryUrl($cat),
                    'name' => $cat->getName(),
                    'level' => $cat->getLevel(),
                    'children' => $this->getChildCategories($cat),
                    'parent_cat' => $category->getId()
                ];
            }
        }

        return $data;
    }

    /**
     * @return \Magento\Framework\View\Element\BlockInterface
     */
    protected function getListBlock()
    {
        $block = $this->getLayout()->createBlock('\Magento\Framework\View\Element\Template');
        $block->assign('currentCategoryId', $this->getCurrentCategoryId());
        $block->assign('checkboxes', ($this->getCategoriesDataHelper()->isShowCheckboxes()) ? '' : '');
        $block->assign('isAjax', (int)$this->getCategoriesDataHelper()->isAjax());
        $block->assign('categoriesBlock', $this);

        return $block;
    }

    /**
     * @param $data
     * @return string
     */
    public function getOlList($data)
    {
        $block = $this->getListBlock();
        $block->setTemplate('GoMage_Navigation::categories/list/ol_list.phtml');
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
        $block->setTemplate('GoMage_Navigation::categories/list/image.phtml');
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
        $block->setTemplate('GoMage_Navigation::categories/list/select.phtml');
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
        $block->setTemplate('GoMage_Navigation::categories/list/image.phtml');
        $block->assign('alignment',
            ($this->getCategoriesDataHelper()->getCategoriesImageAlignment()) ? 'alignment:' . $this->getCategoriesDataHelper()->getCategoriesImageAlignment() : '');
        $block->assign('width',
            ($this->getCategoriesDataHelper()->getCategoriesImageWidth()) ? $this->getCategoriesDataHelper()->getCategoriesImageWidth() : '');
        $block->assign('height',
            ($this->getCategoriesDataHelper()->getCategoriesImageHeight()) ? $this->getCategoriesDataHelper()->getCategoriesImageHeight() : '');
        $block->assign('data', $data);

        return $block->toHtml();
    }

    /**
     * @param $category
     * @return int
     */
    public function getProductsCount($category)
    {
        return $this->categoryResource->getProductCount($category);
    }

    public function isHidecategory($category)
    {
       return $this->getCategoriesDataHelper()->isHideEmptyCategories() && 
           !$this->getProductsCount($category) && !$category->getChildrenCount();
    }
    /**
     * @param $id
     * @return mixed
     */
        public function getCategoryImage($id)
    {
        //Find solution to foreach categories with all data in template
        $category = $this->categoryHelper->getCategoryFactory()->create()->load($id);
        $this->image = $category->getData('image');
        return $category->getImageUrl();
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
            $templateFile = $this->templates->getShowShopByInTemplate($this->getCategoriesDataHelper()->getCategoriesNavigationType());
        } else {
            $templateFile = $this->templates->get($this->getCategoriesDataHelper()->getCategoriesNavigationType());
        }
        $this->setTemplate($templateFile);

        return parent::_beforeToHtml();
    }

    /**
     * Set block location according settings
     */
    protected function setLocation()
    {
        if (!$this->getDataHelper()->isEnable() || !$this->getCategoriesDataHelper()->isShowCategories()) {
            return;
        }
        if ($this->getCategoriesDataHelper()->getCategoriesBlockLocation() == \GoMage\Navigation\Model\Config\Source\Place::CONTENT &&
            $this->getPageLayout() == '1column') {
            $this->moveColumn('main');
            $this->canShowCategories = true;
            return;
        } else {
            $this->getLayout()->unsetChild('main', 'gomage.categories.column');
        }

        if ($this->getCategoriesDataHelper()->getCategoriesBlockLocation() == \GoMage\Navigation\Model\Config\Source\Place::LEFT_COLUMN &&
            $this->getPageLayout() == '2columns-left') {
            $this->getLayout()->reorderChild('sidebar.main', 'gomage.categories', 0);
            $this->canShowCategories = true;
            return;
        }
        if ($this->getCategoriesDataHelper()->getCategoriesBlockLocation() == \GoMage\Navigation\Model\Config\Source\Place::CONTENT &&
            $this->getPageLayout() == '2columns-left') {
            $this->moveBlock('main');
            $this->getLayout()->reorderChild('main', 'gomage.categories', 0);
            $this->canShowCategories = true;
            return;
        }

        if ($this->getCategoriesDataHelper()->getCategoriesBlockLocation() == \GoMage\Navigation\Model\Config\Source\Place::LEFT_COLUMN &&
            $this->getPageLayout() == '3columns') {
            $this->getLayout()->reorderChild('sidebar.main', 'gomage.categories', 0);
            $this->canShowCategories = true;
            return;
        }

        if ($this->getCategoriesDataHelper()->getCategoriesBlockLocation() == \GoMage\Navigation\Model\Config\Source\Place::RIGHT_COLUMN &&
            $this->getPageLayout() == '2columns-right') {
            $this->getLayout()->reorderChild('sidebar.main', 'gomage.categories', 0);
            $this->canShowCategories = true;
            return;
        }

        if ($this->getCategoriesDataHelper()->getCategoriesBlockLocation() == \GoMage\Navigation\Model\Config\Source\Place::CONTENT &&
            $this->getPageLayout() == '2columns-right') {
            $this->moveBlock('main');
            $this->getLayout()->reorderChild('main', 'gomage.categories', 0);
            $this->canShowCategories = true;
            return;
        }

        if ($this->getCategoriesDataHelper()->getCategoriesBlockLocation() == \GoMage\Navigation\Model\Config\Source\Place::RIGHT_COLUMN &&
            $this->getPageLayout() == '3columns') {
            $this->moveBlock('sidebar.additional');
            $this->canShowCategories = true;
            return;
        }

        if ($this->getCategoriesDataHelper()->getCategoriesBlockLocation() == \GoMage\Navigation\Model\Config\Source\Place::CONTENT &&
            $this->getPageLayout() == '3columns') {
            $this->moveBlock('main');
            $this->canShowCategories = true;
            return;
        }
    }

    public function moveColumn($parent)
    {
        $this->getLayout()->unsetChild('main', 'gomage.categories.column');
        $this->getLayout()->setChild($parent, 'gomage.categories.column', 'gomage.categories.moved');
        $this->getLayout()->reorderChild($parent, 'gomage.categories.column', 0);
    }
    /**
     * @param $parent
     */
    protected function moveBlock($parent)
    {
        $this->getLayout()->unsetChild('sidebar.main', 'gomage.categories');
        $this->getLayout()->setChild($parent, 'gomage.categories', 'gomage.categories.moved');
        $this->getLayout()->reorderChild($parent, 'gomage.categories', 0);
    }

    public function getImage()
    {
        return $this->image;
    }
}
