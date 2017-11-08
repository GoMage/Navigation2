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

        $this->setTemplate('GoMage_Navigation::layer/view.phtml');

        return parent::_beforeToHtml();
    }

    public function setLocation()
    {
        if (!$this->dataHelper->isEnable()) {
            return ;
        }

        if ($this->dataHelper->getShowShopByIn() == \GoMage\Navigation\Model\Config\Source\Place::LEFT_COLUMN ||
            $this->dataHelper->getShowShopByIn() == \GoMage\Navigation\Model\Config\Source\Place::RIGHT_COLUMN) {

            $sidebarPosition = $this->dataHelper->getBlockLocation($this->dataHelper->getShowShopByIn());
            $this->pageConfig->addPageAsset('GoMage_Navigation::css/gomage-navigation-sidebar-position-' . $sidebarPosition . '.css');
            return ;
        }

        if ($this->dataHelper->getShowShopByIn() == \GoMage\Navigation\Model\Config\Source\Place::CONTENT) {
            $this->getLayout()->unsetChild('sidebar.main', 'catalog.leftnav');
            $this->getLayout()->setChild('main', 'catalog.leftnav', 'catalog.leftnav.content');
            $this->getLayout()->reorderChild('main', 'catalog.leftnav', 1);
        }
    }
}
