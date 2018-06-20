<?php

namespace GoMage\Navigation\Block;

class Navigation extends \Magento\LayeredNavigation\Block\Navigation
{
    /**
     * @var \Magento\Framework\App\RequestInterface
     */
    protected $request;

    /**
     * @var array
     */
    protected $activeFilters;

    /**
     * @var \GoMage\Navigation\Helper\Data
     */
    protected $dataHelper;

    /**
     * @var string
     */
    protected $pageLayout;

    /**
     * @var bool
     */
    protected $canShowNavigation = false;

    /**
     * @var \Magento\Catalog\Model\Layer
     */
    protected $catalogLayer;

    /**
     * @var \GoMage\Navigation\Helper\CategoryData
     */
    protected $categoryHelper;

    /**
     * @var string
     */
    protected $categoriesHtml;

    /**
     * @var \GoMage\Navigation\Helper\NavigationViewData
     */
    protected $navigationViewHelper;

    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Catalog\Model\Layer\Resolver $layerResolver
     * @param \Magento\Catalog\Model\Layer\FilterList $filterList
     * @param \Magento\Catalog\Model\Layer\AvailabilityFlagInterface $visibilityFlag
     * @param \GoMage\Navigation\Helper\Data $dataHelper
     * @param \GoMage\Navigation\Helper\CategoryData $categoryHelper
     * @param \GoMage\Navigation\Helper\NavigationViewData $navigationViewHelper
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Catalog\Model\Layer\Resolver $layerResolver,
        \Magento\Catalog\Model\Layer\FilterList $filterList,
        \Magento\Catalog\Model\Layer\AvailabilityFlagInterface $visibilityFlag,
        \GoMage\Navigation\Helper\Data $dataHelper,
        \GoMage\Navigation\Helper\CategoryData $categoryHelper,
        \GoMage\Navigation\Helper\NavigationViewData $navigationViewHelper,
        array $data = []
    ) {
        $this->catalogLayer = $layerResolver->get();
        $this->filterList = $filterList;
        $this->visibilityFlag = $visibilityFlag;
        $this->request = $context->getRequest();
        $this->dataHelper = $dataHelper;
        $this->categoryHelper = $categoryHelper;
        $this->navigationViewHelper = $navigationViewHelper;

        parent::__construct($context, $layerResolver, $filterList, $visibilityFlag, $data);
        $this->setLocation();
    }

    /**
     * @return \GoMage\Navigation\Helper\Data
     */
    public function getDataHelper()
    {
        return $this->dataHelper;
    }

    /**
     * @return \GoMage\Navigation\Helper\CategoryData
     */
    public function getCategoryDataHelper()
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

    /**
     * @return \Magento\Framework\View\Element\BlockInterface
     */
    public function getRenderBlock()
    {
        return $this->getLayout()->createBlock('GoMage\Navigation\Block\Navigation\FilterRenderer');
    }

    /**
     * @return \Magento\Framework\View\Element\BlockInterface
     */
    public function getStateBlock()
    {
        $state = $this->getLayout()->createBlock('GoMage\Navigation\Block\Navigation\State');
        $this->setChild('state',$state);
        return $state;
    }

    /**
     * @return string
     */
    public function getCategoriesHtml()
    {

        if (empty($this->categoriesHtml) && $this->getCategoryDataHelper()->isShowCategoryInShopBy()) {
            $this->categoriesHtml = $this->getLayout()->createBlock('GoMage\Navigation\Block\Categories')->toHtml();
        }

        return $this->categoriesHtml;
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
     * @return array
     */
    public function getExpandedFilters()
    {
        $data = [];
        $cnt = 0;
        if($this->request->get('collapsed_expanded')) {
            $filterOpened = explode('_', $this->request->get('collapsed_expanded'));
        }
            foreach ($this->getFilters() as $filter) {
                if ($filter->getItemsCount()) {
                    if (
                        (!(bool)$filter->getGomageIsCollapsed() && !$this->request->get('collapsed_expanded'))
                        || ($this->request->get('collapsed_expanded') && in_array(urlencode(strtolower($filter->getName())), $filterOpened))
                    ) {
                        $data[] = $cnt;
                    }
                    $cnt++;
                }
            }

        return $data;
    }

    /**
     * @return int
     */
    public function getFiltersWithItemsCount()
    {
        $cnt = 0;
        foreach ($this->getFilters() as $filter) {
            if ($filter->getItemsCount()) {
                $cnt++;
            }
        }

        return $cnt;
    }

    /**
     * @return string
     */
    public function getItemWidthStyle()
    {
        $itemStyle = '';
        if ($this->getDataHelper()->getShowShopByIn() == \GoMage\Navigation\Model\Config\Source\Place::CONTENT &&
            $this->getDataHelper()->getContentFilterType() == \GoMage\Navigation\Model\Config\Source\Content\Filter\Type::COLUMNS) {
            $itemStyle = 'width: ' . round(100 / $this->getFiltersWithItemsCount()) . '%';
        }

        return $itemStyle;
    }

    /**
     * @return string
     */
    public function getItemClass()
    {
        $itemClass = '';
        if ($this->getDataHelper()->getShowShopByIn() == \GoMage\Navigation\Model\Config\Source\Place::CONTENT &&
            $this->getDataHelper()->getContentFilterType() == \GoMage\Navigation\Model\Config\Source\Content\Filter\Type::COLUMNS) {
            $itemClass = 'gan-column-item';
        }

        if ($this->getDataHelper()->getShowShopByIn() == \GoMage\Navigation\Model\Config\Source\Place::CONTENT &&
            $this->getDataHelper()->getContentFilterType() == \GoMage\Navigation\Model\Config\Source\Content\Filter\Type::ROWS) {
            $itemClass = 'gan-row-item';
        }

        return $itemClass;
    }

    /**
     * @return string
     */
    public function getContainerClass()
    {
        $containerClass = '';
        if ($this->getDataHelper()->getShowShopByIn() == \GoMage\Navigation\Model\Config\Source\Place::CONTENT &&
            $this->getDataHelper()->getContentFilterType() == \GoMage\Navigation\Model\Config\Source\Content\Filter\Type::COLUMNS) {
            $containerClass = 'gan-column-container';
        }

        if ($this->getDataHelper()->getShowShopByIn() == \GoMage\Navigation\Model\Config\Source\Place::CONTENT &&
            $this->getDataHelper()->getContentFilterType() == \GoMage\Navigation\Model\Config\Source\Content\Filter\Type::ROWS) {
            $containerClass = 'gan-row-container';
        }

        return $containerClass;
    }

    /**
     * @return $this
     */
    protected function _beforeToHtml()
    {
        if (!$this->getDataHelper()->isEnable()) {
            $this->setTemplate('Magento_LayeredNavigation::layer/view.phtml');
            return parent::_beforeToHtml();
        }

        if ($this->canShowNavigation) {
            $this->setTemplate('GoMage_Navigation::layer/view.phtml');
        } else {
            $this->setTemplate('GoMage_Navigation::layer/viewnocanshow.phtml');
        }

        return parent::_beforeToHtml();
    }

    /**
     * Set block location according settings
     */
    protected function setLocation()
    {
        if (!$this->getDataHelper()->isEnable()) {
            return ;
        }

        if ($this->getDataHelper()->getShowShopByIn() == \GoMage\Navigation\Model\Config\Source\Place::CONTENT &&
            $this->getPageLayout() == '1column' ) {
            $this->moveBlock('main');
            $this->canShowNavigation = true;
            return ;
        }

        if ($this->getDataHelper()->getShowShopByIn() == \GoMage\Navigation\Model\Config\Source\Place::LEFT_COLUMN &&
            $this->getPageLayout() == '2columns-left' ) {
            $this->canShowNavigation = true;
            return ;
        }

        if ($this->getDataHelper()->getShowShopByIn() == \GoMage\Navigation\Model\Config\Source\Place::CONTENT &&
            $this->getPageLayout() == '2columns-left' ) {
            $this->moveBlock('main');
            $this->canShowNavigation = true;
            return ;
        }

        if ($this->getDataHelper()->getShowShopByIn() == \GoMage\Navigation\Model\Config\Source\Place::LEFT_COLUMN &&
            $this->getPageLayout() == '3columns' ) {
            $this->canShowNavigation = true;
            return ;
        }

        if ($this->getDataHelper()->getShowShopByIn() == \GoMage\Navigation\Model\Config\Source\Place::RIGHT_COLUMN &&
            $this->getPageLayout() == '2columns-right' ) {
            $this->canShowNavigation = true;
            return ;
        }

        if ($this->getDataHelper()->getShowShopByIn() == \GoMage\Navigation\Model\Config\Source\Place::CONTENT &&
            $this->getPageLayout() == '2columns-right' ) {
            $this->moveBlock('main');
            $this->canShowNavigation = true;
            return ;
        }

        if ($this->getDataHelper()->getShowShopByIn() == \GoMage\Navigation\Model\Config\Source\Place::RIGHT_COLUMN &&
            $this->getPageLayout() == '3columns' ) {
            $this->moveBlock('sidebar.additional');
            $this->canShowNavigation = true;
            return ;
        }

        if ($this->getDataHelper()->getShowShopByIn() == \GoMage\Navigation\Model\Config\Source\Place::CONTENT &&
            $this->getPageLayout()== '3columns' ) {
            $this->moveBlock('main');
            $this->canShowNavigation = true;
            return ;
        }
    }

    /**
     * @param $parent
     */
    protected function moveBlock($parent)
    {
        $this->getLayout()->unsetChild('sidebar.main', 'catalog.leftnav');
        $this->getLayout()->setChild($parent, 'catalog.leftnav', 'catalog.leftnav.moved');
        $this->getLayout()->reorderChild($parent, 'catalog.leftnav', 0);
    }
}
