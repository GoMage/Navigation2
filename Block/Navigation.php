<?php

namespace GoMage\Navigation\Block;

use GoMage\Navigation\Model\Config\Source\NavigationInterface;

class Navigation extends \Magento\LayeredNavigation\Block\Navigation
{
    /**
     * @var \Magento\Framework\App\RequestInterface
     */
    protected $request;

    protected $activeFilters;

    protected $dataHelper;

    protected $pageRepository;

    protected $pageLayout;

    protected $canShowNavigation = false;

    /**
     * Navigation constructor.
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Catalog\Model\Layer\Resolver $layerResolver
     * @param \Magento\Catalog\Model\Layer\FilterList $filterList
     * @param \Magento\Catalog\Model\Layer\AvailabilityFlagInterface $visibilityFlag
     * @param \Magento\Framework\App\RequestInterface $request
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Catalog\Model\Layer\Resolver $layerResolver,
        \Magento\Catalog\Model\Layer\FilterList $filterList,
        \Magento\Catalog\Model\Layer\AvailabilityFlagInterface $visibilityFlag,
        \Magento\Framework\App\RequestInterface $request,
        \GoMage\Navigation\Helper\Data $dataHelper,
        array $data = []
    )
    {
        $this->_catalogLayer = $layerResolver->get();
        $this->filterList = $filterList;
        $this->visibilityFlag = $visibilityFlag;
        $this->request = $request;
        $this->dataHelper = $dataHelper;

        parent::__construct($context, $layerResolver, $filterList, $visibilityFlag, $data);
        $this->setLocation();

    }

    protected function getPageLayout()
    {
        if (empty($this->pageLayout)) {
            $this->pageLayout = $this->getLayout()->getUpdate()->getPageLayout();
        }

        return $this->pageLayout;
    }

    /**
     * @return bool
     */
    public function hasFilters()
    {
        foreach ($this->getFilters() as $filter) {
            if ($filter->getItemsCount()) {
                return true;
            }
        }
        return false;
    }

    public function getExpandedFilters()
    {
        $data = [];
        $cnt = 0;
        foreach ($this->getFilters() as $filter) {
            if($filter->getItemsCount()) {
                if ($filter->getGomageIsCollapsed()) {
                    $data[] = $cnt;
                }
                $cnt++;
            }
        }

        return '[' . implode(',', $data) . ']';
    }

    protected function _beforeToHtml()
    {
        if (!$this->dataHelper->isEnable()) {
            $this->setTemplate('Magento_LayeredNavigation::layer/view.phtml');
            return parent::_beforeToHtml();
        }

        if($this->canShowNavigation) {
            $this->setTemplate('GoMage_Navigation::layer/view.phtml');
        }

        return parent::_beforeToHtml();
    }

    public function setLocation()
    {
        if (!$this->dataHelper->isEnable()) {
            return ;
        }

        if ($this->dataHelper->getShowShopByIn() == \GoMage\Navigation\Model\Config\Source\Place::CONTENT &&
            $this->getPageLayout() == '1column' ) {
            $this->moveBlock('main');
            $this->canShowNavigation = true;
            return ;
        }

        if ($this->dataHelper->getShowShopByIn() == \GoMage\Navigation\Model\Config\Source\Place::LEFT_COLUMN &&
            $this->getPageLayout() == '2columns-left' ) {
            $this->canShowNavigation = true;
            return ;
        }

        if ($this->dataHelper->getShowShopByIn() == \GoMage\Navigation\Model\Config\Source\Place::CONTENT &&
            $this->getPageLayout() == '2columns-left' ) {
            $this->moveBlock('main');
            $this->canShowNavigation = true;
            return ;
        }

        if ($this->dataHelper->getShowShopByIn() == \GoMage\Navigation\Model\Config\Source\Place::LEFT_COLUMN &&
            $this->getPageLayout() == '3columns' ) {
            $this->canShowNavigation = true;
            return ;
        }

        if ($this->dataHelper->getShowShopByIn() == \GoMage\Navigation\Model\Config\Source\Place::RIGHT_COLUMN &&
            $this->getPageLayout() == '2columns-right' ) {
            $this->canShowNavigation = true;
            return ;
        }

        if ($this->dataHelper->getShowShopByIn() == \GoMage\Navigation\Model\Config\Source\Place::CONTENT &&
            $this->getPageLayout() == '2columns-right' ) {
            $this->moveBlock('main');
            $this->canShowNavigation = true;
            return ;
        }

        if ($this->dataHelper->getShowShopByIn() == \GoMage\Navigation\Model\Config\Source\Place::RIGHT_COLUMN &&
            $this->getPageLayout() == '3columns' ) {
            $this->moveBlock('sidebar.additional');
            $this->canShowNavigation = true;
            return ;
        }

        if ($this->dataHelper->getShowShopByIn() == \GoMage\Navigation\Model\Config\Source\Place::CONTENT &&
            $this->getPageLayout()== '3columns' ) {
            $this->moveBlock('main');
            $this->canShowNavigation = true;
            return ;
        }
    }

    protected function moveBlock($parent)
    {
        $this->getLayout()->unsetChild('sidebar.main', 'catalog.leftnav');
        $this->getLayout()->setChild($parent, 'catalog.leftnav', 'catalog.leftnav.moved');
        $this->getLayout()->reorderChild($parent, 'catalog.leftnav', 0);
    }

    protected function setPageAssets()
    {
        $sidebarPosition = $this->dataHelper->getBlockLocation($this->dataHelper->getShowShopByIn());
        $this->pageConfig->addPageAsset('GoMage_Navigation::css/gomage-navigation-sidebar-position-' . $sidebarPosition . '.css');
    }
}
