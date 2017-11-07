<?php
namespace GoMage\Navigation\Block\Navigation\Categories;

class Main extends \Magento\Framework\View\Element\Template
{

     protected $_categoryHelper;
     protected $categoryFlatConfig;
     protected $topMenu;
     protected $dataHelper;
     protected $templates;
     protected $categoryResource;

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
        if ($this->dataHelper->isEnable() &&
            $this->dataHelper->isShowCategories() &&
            $this->dataHelper->getCategoriesBlockLocation() == static::LOCATION) {

            $templateFile = $this->templates->get($this->dataHelper->getCategoriesNavigationType());
            $this->setTemplate($templateFile);
        }

        return parent::_beforeToHtml();
    }
}