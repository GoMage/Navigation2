<?php

namespace GoMage\Navigation\Block;

use GoMage\Navigation\Model\Config\Source\NavigationInterface;

class NavigationRight extends \Magento\LayeredNavigation\Block\Navigation
{
    /**
     * @var \Magento\Framework\App\RequestInterface
     */
    protected $request;

    protected $activeFilters;

    protected $dataHelper;
    protected $location;

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
        $location,
        array $data = []
    )
    {
        $this->_catalogLayer = $layerResolver->get();
        $this->filterList = $filterList;
        $this->visibilityFlag = $visibilityFlag;
        $this->request = $request;
        $this->dataHelper = $dataHelper;
        $this->location = $location;
        parent::__construct($context, $layerResolver, $filterList, $visibilityFlag, $data);
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
        if($this->dataHelper->getShowShopByIn() == $this->location) {
            $this->setTemplate('GoMage_Navigation::layer/view.phtml');
            return parent::_beforeToHtml();
        }

        return '';
    }

    protected function _prepareLayout()
    {
        if($this->dataHelper->getShowShopByIn() == $this->location) {
            return parent::_prepareLayout();
        }

        return '';
    }
}
