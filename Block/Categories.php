<?php
namespace GoMage\Navigation\Block;

class Categories extends \Magento\Framework\View\Element\Template
{

     protected $_categoryHelper;
     protected $categoryFlatConfig;
     protected $topMenu;
     protected $dataHelper;
     protected $templates;
     protected $categoryResource;
     protected $pageLayout;
     protected $canShowCategories;

    /**
     * Main constructor.
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Catalog\Helper\Category $categoryHelper
     * @param \Magento\Catalog\Model\Indexer\Category\Flat\State $categoryFlatState
     * @param \Magento\Theme\Block\Html\Topmenu $topMenu
     * @param \GoMage\Navigation\Helper\Data $dataHelper
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Catalog\Helper\Category $categoryHelper,
        \Magento\Catalog\Model\Indexer\Category\Flat\State $categoryFlatState,
        \Magento\Theme\Block\Html\Topmenu $topMenu,
        \GoMage\Navigation\Helper\Data $dataHelper,
        \GoMage\Navigation\Model\Config\Source\Category\Templates $templates,
        \Magento\Catalog\Model\ResourceModel\Category $categoryResource
    ) {

        $this->_categoryHelper = $categoryHelper;
        $this->categoryFlatConfig = $categoryFlatState;
        $this->topMenu = $topMenu;
        $this->dataHelper = $dataHelper;
        $this->templates = $templates;
        $this->categoryResource = $categoryResource;
        parent::__construct($context);
        $this->setLocation();
    }

    protected function getPageLayout()
    {
        if (empty($this->pageLayout)) {
            $this->pageLayout = $this->getLayout()->getUpdate()->getPageLayout();
        }

        return $this->pageLayout;
    }

    public function getCategoryHelper()
    {
        return $this->_categoryHelper;
    }

   public function getStoreCategories($sorted = false, $asCollection = false, $toLoad = true)
    {
        return $this->_categoryHelper->getStoreCategories($sorted , $asCollection, $toLoad);
    }

    public function getChildCategories($category, $data = false)
    {
        $html = '';
        if ($this->categoryFlatConfig->isFlatEnabled() && $category->getUseFlatResource()) {
            $subcategories = (array)$category->getChildrenNodes();
        } else {
            $subcategories = $category->getChildren();
        }

        foreach ($subcategories as $cat) {

            $cnt = $this->getProductsCount($cat);
            if(!$cnt && !$cat->getChildrenCount()) {
                continue;
            }

            $html .= '<li>';
            $html .= '<a href="' . $this->getCategoryHelper()->getCategoryUrl($cat) . '">' . $cat->getName() . '</a>';
            $html .= $this->getChildCategories($cat);
            $html .= '</li>';
        }

        if (!empty($html)) {
            $html = '<ul>' . $html . '</ul>';
        }

        return $html;
    }

    public function getProductsCount($category)
    {
        return $this->categoryResource->getProductCount($category);
    }

    protected function _beforeToHtml()
    {
        if (!$this->dataHelper->isEnable() || !$this->dataHelper->isShowCategories() || !$this->canShowCategories) {
            return parent::_beforeToHtml();
        }

        $templateFile = $this->templates->get($this->dataHelper->getCategoriesNavigationType());
        $this->setTemplate($templateFile);

        return parent::_beforeToHtml();
    }

    protected function setLocation()
    {
        if (!$this->dataHelper->isEnable() || !$this->dataHelper->isShowCategories()) {
            return ;
        }

        if ($this->dataHelper->getCategoriesBlockLocation() == \GoMage\Navigation\Model\Config\Source\Place::CONTENT &&
            $this->getPageLayout() == '1column' ) {
            $this->moveBlockToContent();
            $this->setPageAssets();
            $this->canShowCategories = true;
            return ;
        }

        if ($this->dataHelper->getCategoriesBlockLocation() == \GoMage\Navigation\Model\Config\Source\Place::LEFT_COLUMN &&
            $this->getPageLayout() == '2columns-left' ) {
            $this->setPageAssets();
            $this->canShowCategories = true;
            return ;
        }

        if ($this->dataHelper->getCategoriesBlockLocation() == \GoMage\Navigation\Model\Config\Source\Place::RIGHT_COLUMN &&
            $this->getPageLayout() == '2columns-right' ) {
            $this->setPageAssets();
            $this->canShowCategories = true;
            return ;
        }

        if ($this->dataHelper->getCategoriesBlockLocation() == \GoMage\Navigation\Model\Config\Source\Place::CONTENT &&
            $this->getPageLayout()== '3columns' ) {
            $this->moveBlockToContent();
            $this->setPageAssets();
            $this->canShowCategories = true;
            return ;
        }
    }

    protected function moveBlockToContent()
    {
        $this->getLayout()->unsetChild('columns', 'gomage.categories.left');
        $this->getLayout()->setChild('main', 'gomage.categories.left', 'gomage.categories.left.content');
        $this->getLayout()->reorderChild('main', 'gomage.categories.left', 0);
    }

    protected function setPageAssets()
    {
        $categoriesPosition = $this->dataHelper->getBlockLocation($this->dataHelper->getCategoriesBlockLocation());
        $this->pageConfig->addPageAsset('GoMage_Navigation::css/gomage-navigation-categories-position-' . $categoriesPosition . '.css');
    }
}
