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

    public function getChildCategories($category)
    {
        $html = [];
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

            $html[] = [
                'entity_id' => $cat['entity_id'],
                'url' => $this->getCategoryHelper()->getCategoryUrl($cat),
                'name' => $cat->getName(),
                'children' => $this->getChildCategories($cat)
            ];

        }

        return $html;
    }

    public function getOlList($data)
    {
        $checkboxes = ($this->dataHelper->isShowCheckboxes()) ? '' : '';
        $html = '';
        foreach ($data as $category) {
            $html .= '<ol '. $checkboxes .'><li><a href="'. $category['url'] . '">' . $category['name'] . '</a>';
            if(is_array($category['children'])) {
                $html .= $this->getOlList($category['children']);
            }
            $html .= '</li></ol>';
        }

        return $html;
    }

    public function getImageCategoriesList($data)
    {
        $html = '';
        foreach ($data as $category) {

            $alignment = ($this->dataHelper->getCategoriesImageAlignment()) ? 'alignment:' . $this->dataHelper->getCategoriesImageAlignment() : '';
            $width = ($this->dataHelper->getCategoriesImageWidth()) ? $this->dataHelper->getCategoriesImageWidth() : '';
            $height = ($this->dataHelper->getCategoriesImageHeight()) ? $this->dataHelper->getCategoriesImageHeight() : '';
            $name = ($this->dataHelper->isShowImageName()) ? $category['name'] : '';
            $html .= '<ol><li><a href="' . $category['url'] . '">
            <img 
                src=" ' . $this->getCategoryImage($category['entity_id']) . ' "
                width="' . $width . '"
                height="' . $height . '"
                >' . $name . '
            </a>';
            if(is_array($category['children'])) {
                $html .= $this->getImageCategoriesList($category['children']);
            }
            $html .= '</li></ol>';
        }

        return $html;
    }

    public function getProductsCount($category)
    {
        return $this->categoryResource->getProductCount($category);
    }

    public function getCategoryImage($id)
    {
        $category = \Magento\Framework\App\ObjectManager::getInstance()
            ->create('Magento\Catalog\Model\Category')->load($id);

        return $category->getImageUrl();
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
            $this->moveBlock('main');
            $this->canShowCategories = true;
            return ;
        }

        if ($this->dataHelper->getCategoriesBlockLocation() == \GoMage\Navigation\Model\Config\Source\Place::LEFT_COLUMN &&
            $this->getPageLayout() == '2columns-left' ) {
            $this->getLayout()->reorderChild('sidebar.main', 'gomage.categories', 0);
            $this->canShowCategories = true;
            return ;
        }

        if ($this->dataHelper->getCategoriesBlockLocation() == \GoMage\Navigation\Model\Config\Source\Place::CONTENT &&
            $this->getPageLayout() == '2columns-left' ) {
            $this->moveBlock('main');
            $this->getLayout()->reorderChild('main', 'gomage.categories', 0);
            $this->canShowCategories = true;
            return ;
        }

        if ($this->dataHelper->getCategoriesBlockLocation() == \GoMage\Navigation\Model\Config\Source\Place::LEFT_COLUMN &&
            $this->getPageLayout() == '3columns' ) {
            $this->getLayout()->reorderChild('sidebar.main', 'gomage.categories', 0);
            $this->canShowCategories = true;
            return ;
        }

        if ($this->dataHelper->getCategoriesBlockLocation() == \GoMage\Navigation\Model\Config\Source\Place::RIGHT_COLUMN &&
            $this->getPageLayout() == '2columns-right' ) {
            $this->getLayout()->reorderChild('sidebar.main', 'gomage.categories', 0);
            $this->canShowCategories = true;
            return ;
        }

        if ($this->dataHelper->getCategoriesBlockLocation() == \GoMage\Navigation\Model\Config\Source\Place::CONTENT &&
            $this->getPageLayout() == '2columns-right' ) {
            $this->moveBlock('main');
            $this->getLayout()->reorderChild('main', 'gomage.categories', 0);
            $this->canShowCategories = true;
            return ;
        }

        if ($this->dataHelper->getCategoriesBlockLocation() == \GoMage\Navigation\Model\Config\Source\Place::RIGHT_COLUMN &&
            $this->getPageLayout() == '3columns' ) {
            $this->moveBlock('sidebar.additional');
            $this->canShowCategories = true;
            return ;
        }

        if ($this->dataHelper->getCategoriesBlockLocation() == \GoMage\Navigation\Model\Config\Source\Place::CONTENT &&
            $this->getPageLayout()== '3columns' ) {
            $this->moveBlock('main');
            $this->canShowCategories = true;
            return ;
        }
    }

    protected function moveBlock($parent)
    {
        $this->getLayout()->unsetChild('sidebar.main', 'gomage.categories');
        $this->getLayout()->setChild($parent, 'gomage.categories', 'gomage.categories.moved');
        $this->getLayout()->reorderChild($parent, 'gomage.categories', 0);
    }

    protected function setPageAssets()
    {
        $categoriesPosition = $this->dataHelper->getBlockLocation($this->dataHelper->getCategoriesBlockLocation());
        $this->pageConfig->addPageAsset('GoMage_Navigation::css/gomage-navigation-categories-position-' . $categoriesPosition . '.css');
    }
}
