<?php

namespace GoMage\Navigation\Block;

use GoMage\Navigation\Model\Config\Source\NavigationInterface;

class Navigation extends \Magento\LayeredNavigation\Block\Navigation
{

    /**
     * @var \Magento\Framework\App\RequestInterface
     */
    protected $request;

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
        array $data = []
    )
    {
        $this->_catalogLayer = $layerResolver->get();
        $this->filterList = $filterList;
        $this->visibilityFlag = $visibilityFlag;
        $this->request = $request;
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


    /**
     * @return $this
     */
    protected function _prepareLayout()
    {
        $collection = $this->getLayer()->getProductCollection();
        $base_select = array();
        $request = $this->request;
        $base_select = $this->getLayer()->getBaseSelect();
        if (!isset($base_select['collection'])) {
            $base_select['collection'] = clone $collection;
        }
        if ($request->getParam(NavigationInterface::ATTRIBUTE_CATEGORY)) {
            $base_select[NavigationInterface::ATTRIBUTE_CATEGORY] = clone $collection->getSelect();
        }
        foreach ($this->getFilters() as $filter) {
            $attribute = $filter->getAttributeModel();
            if ($code = $attribute->getAttributeCode()) {
                if ($request->getParam($code)) {
                    $base_select[$code] = clone $collection->getSelect();
                }
            }
        }
        $this->getLayer()->setBaseSelect($base_select);

        return parent::_prepareLayout();
    }

}
