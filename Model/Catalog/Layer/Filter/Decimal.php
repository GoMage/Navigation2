<?php

namespace GoMage\Navigation\Model\Catalog\Layer\Filter;

/**
 * Class Decimal
 * @package GoMage\Navigation\Model\Catalog\Layer\Filter
 */
class Decimal extends \Magento\Catalog\Model\Layer\Filter\Decimal implements FilterInterface
{
    /**
     * @var \Magento\Framework\App\RequestInterface
     */
    protected $request;

    /**
     * @var \Magento\Catalog\Model\Layer\Filter\DataProvider\Decimal
     */
    protected $dataProvider;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var \Magento\Framework\Pricing\PriceCurrencyInterface
     */
    protected $priceCurrency;

    /**
     * @var \GoMage\Navigation\Helper\Data
     */
    protected $dataHelper;

    /**
     * @var \GoMage\Navigation\Model\Config\Source\Filter\Templates
     */
    protected $filterTemplates;

    /**
     * @var \GoMage\Navigation\Helper\Url
     */
    protected $urlHelper;

    /**
     * @param \Magento\Catalog\Model\Layer\Filter\ItemFactory $filterItemFactory
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Catalog\Model\Layer $layer
     * @param \Magento\Catalog\Model\Layer\Filter\Item\DataBuilder $itemDataBuilder
     * @param \Magento\Framework\Pricing\PriceCurrencyInterface $priceCurrency
     * @param \Magento\Catalog\Model\Layer\Filter\DataProvider\DecimalFactory $dataProviderFactory
     * @param \Magento\Framework\App\RequestInterface $request
     * @param \GoMage\Navigation\Helper\Data $dataHelper
     * @param \GoMage\Navigation\Model\Config\Source\Filter\Templates $filterTemplates
     * @param \GoMage\Navigation\Helper\Url $urlHelper
     * @param array $data
     */
    public function __construct(
        \Magento\Catalog\Model\Layer\Filter\ItemFactory $filterItemFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Catalog\Model\Layer $layer,
        \Magento\Catalog\Model\Layer\Filter\Item\DataBuilder $itemDataBuilder,
        \Magento\Framework\Pricing\PriceCurrencyInterface $priceCurrency,
        \Magento\Catalog\Model\Layer\Filter\DataProvider\DecimalFactory $dataProviderFactory,
        \Magento\Framework\App\RequestInterface $request,
        \GoMage\Navigation\Helper\Data $dataHelper,
        \GoMage\Navigation\Model\Config\Source\Filter\Templates $filterTemplates,
        \GoMage\Navigation\Helper\Url $urlHelper,
        array $data = []
    ) {
        $this->request = $request;
        $this->storeManager = $storeManager;
        $this->priceCurrency = $priceCurrency;
        $this->dataHelper = $dataHelper;
        $this->filterTemplates = $filterTemplates;
        $this->urlHelper = $urlHelper;
        parent::__construct($filterItemFactory, $storeManager, $layer, $itemDataBuilder, $priceCurrency, $dataProviderFactory, $data);
        $this->dataProvider = $dataProviderFactory->create(['layer' => $this->getLayer()]);
    }

    /**
     * @param \Magento\Framework\App\RequestInterface $request
     * @return $this
     */
    public function apply(\Magento\Framework\App\RequestInterface $request)
    {
        if (!$this->dataHelper->isEnable() ||
            $this->getAttributeModel()->getBackendModel() != 'Magento\Catalog\Model\Product\Attribute\Backend\Price') {
            return parent::apply($request);
        }

        if (!in_array($this->getGomageFilterTemplate(), $this->getTemplatesArray())) {
            return parent::apply($request);
        }

        $filter = $request->getParam($this->getRequestVar());
        if (!$filter || is_array($filter)) {
            return $this;
        }

        $filter = explode('-', $filter);
        if (count($filter) != 2) {
            return $this;
        }

        $from = (int) $filter[0];
        $to = (int) $filter[1];

        $this->dataProvider->getResource()->applyDecimalRangeFilterToCollection($this, $from, $to);

        $this->getLayer()
            ->getState()
            ->addFilter(
                $this->_createItem($this->_renderRangeLabel(empty($from) ? 0 : $from, $to), $filter)
            );

        return $this;
    }

    /**
     * @return bool
     */
    public function isActive()
    {
        if ($this->request->getParam($this->_requestVar)) {
            return true;
        }

        return false;
    }

    /**
     * @return mixed
     */
    protected function getAttributeValuesData()
    {
        return $this->getCollection()->getAllAttributeValues($this->getAttributeModel());
    }

    /**
     * @return bool
     */
    public function isCategoryFilter()
    {
        return false;
    }
    /**
     * @return bool|int
     */
    public function getMinBasePrice()
    {
        $data = $this->getAttributeValuesData();

        if(empty($data)) {
            return false;
        }

        $value = min($data);
        return (int) reset($value);
    }

    /**
     * @return bool|int
     */
    public function getMaxBasePrice()
    {
        $data = $this->getAttributeValuesData();

        if(empty($data)) {
            return false;
        }

        $value = max($data);
        return (int) end($value);
    }

    /**
     * @return mixed
     */
    protected function getCollection()
    {
        return $this->getLayer()->getCollectionProvider()->getCollection($this->getLayer()->getCurrentCategory());
    }

    /**
     * @return mixed|string
     */
    public function getSingleValue()
    {
        if ($value = $this->request->getParam($this->getRequestVar())) {
            return str_replace('-', ';', $value);
        }
        $value =  $this->_renderItemLabel($this->getMinBasePrice(), $this->getMaxBasePrice());
        return str_replace('-', ';', $value);

    }

    /**
     * @return array
     */
    protected function getTemplatesArray()
    {
        $templatesArray[] = $this->filterTemplates->get(\GoMage\Navigation\Model\Config\Source\Navigation::SLIDER);
        $templatesArray[] = $this->filterTemplates->get(\GoMage\Navigation\Model\Config\Source\Navigation::SLIDER_INPUT);
        $templatesArray[] = $this->filterTemplates->get(\GoMage\Navigation\Model\Config\Source\Navigation::INPUT);

        return $templatesArray;
    }

    /**
     * @return $this|\Magento\Catalog\Model\Layer\Filter\AbstractFilter
     */
    protected function _initItems()
    {
        if (!in_array($this->getGomageFilterTemplate(), $this->getTemplatesArray())) {
            return parent::_initItems();
        }

        $minPrice = $this->getMinBasePrice();
        $maxPrice = $this->getMaxBasePrice();

        if(empty($minPrice) || empty($maxPrice)) {
            return parent::_initItems();
        }

        $items[] = $this->_createItem($this->getAttributeModel()->getAttributeCode(), $minPrice . '-' . $maxPrice, 1);
        $this->_items = $items;
        return $this;
    }

    /**
     * @return mixed
     */
    protected function getCurrentCurrency()
    {
        return $this->storeManager->getStore()->getCurrentCurrency();
    }

    /**
     * @return mixed
     */
    public function getCurrencySymbol()
    {
        return $this->getCurrentCurrency()->getCurrencySymbol();
    }

    /**
     * @param $fromPrice
     * @param $toPrice
     * @return \Magento\Framework\Phrase
     */
    protected function _renderRangeLabel($fromPrice, $toPrice)
    {
        if ($fromPrice != $toPrice) {
            $toPrice -= .01;
        }
       return $this->_renderItemLabel($fromPrice, $toPrice);
       // return __('%1 - %2', $formattedFromPrice, $this->priceCurrency->format($toPrice));
    }

    /**
     * @return mixed
     */
    public function getRemoveUrl()
    {
        return $this->urlHelper->getFilterRemoveUrl($this);
    }
}
