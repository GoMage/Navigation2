<?php

namespace GoMage\Navigation\Block\Navigation;

use GoMage\Navigation\Model\Catalog\Layer\Filter\FilterInterface;
use Magento\Framework\View\Element\Template;
use GoMage\Navigation\Model\Config\Source\NavigationInterface;

class FilterRenderer extends Template implements FilterRendererInterface
{


    /**
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry;

    /**
     * @var array|null
     */
    protected $_preferredItems = null;

    /**
     * @var $_filterType
     */
    protected $_filterType;

    /**
     * @var \Magento\Eav\Model\Entity\Attribute
     */
    protected $_renderLayered;

    /**
     * @var \Magento\Framework\App\RequestInterface
     */
    protected $_request;

    /**
     * @var \GoMage\Navigation\Helper\Url
     */
    protected $_urlHelper;

    /**
     * FilterRenderer constructor.
     * @param Template\Context $context
     * @param \Magento\Framework\Registry $coreRegistry
     * @param \Magento\Swatches\Block\LayeredNavigation\RenderLayered $renderLayered
     * @param \Magento\Framework\App\RequestInterface $request
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Swatches\Block\LayeredNavigation\RenderLayered $renderLayered,
        \Magento\Framework\App\RequestInterface $request,
        \GoMage\Navigation\Helper\Url $urlHelper,
        array $data = array()
    )
    {
        $this->_coreRegistry = $coreRegistry;
        $this->_request = $request;
        $this->_urlHelper = $urlHelper;
        $this->_renderLayered = $renderLayered;
        
        parent::__construct($context, $data);
    }

    /**
     * @return null
     */
    protected function _initPreferredFilterItems()
    {
        $preferredBrand = $this->_getPreferredBrand();

        if (!$preferredBrand) {
            $this->_preferredItems = [];
            return;
        }

        $this->_preferredItems = explode(",", $preferredBrand);
    }

    /**
     * @return mixed
     */
    protected function _getPreferredBrand()
    {
        return $this->_getCategory()->getData('preferred_brand');
    }

    /**
     * @return \GoMage\CategoryGrid\Model\Category
     */
    protected function _getCategory()
    {
        return $this->_coreRegistry->registry('current_category');
    }

    /**
     * @param FilterInterface $filter
     * @return string
     */
    public function render(FilterInterface $filter)
    {
        $this->setTemplate($filter->getGomageFilterTemplate());

        $filtersPreferred = [];
        $filtersCommon = [];
        $key = -1;

        foreach ($filter->getItems() as $item) {
            $key++;

            $this->checkIsActive($item, $filter->getRequestVar());
            $item->setGomageUrl($this->_urlHelper->getItemUrl($item));

            if ($this->_isPreferred($item)) {
                $item->setIsPreferred(true);
                $filtersPreferred[$key] = $item;
                continue;
            }

            $filtersCommon[$key] = $item;
        }

        $filters = $filtersPreferred + $filtersCommon;
        $this->assign('filterItems', $filters);

        $html = $this->_toHtml();
        $this->assign('filterItems', []);
        return $html;
    }

    /**
     * @param \GoMage\Navigation\Model\Catalog\Layer\Filter\Item $item
     * @return boolean
     */
    public function _isPreferred($item)
    {
        if (is_null($this->_preferredItems)) {
            $this->_initPreferredFilterItems();
        }

        return in_array($item->getValue(), $this->_preferredItems);
    }

    /**
     * @return int
     */
    public function getStep()
    {
        return NavigationInterface::PRICE_SLIDER_STEP;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->_filterType;
    }

    /**
     * @return string
     */
    public function getSliderSkin()
    {
        return NavigationInterface::SLIDER_SKIN;
    }

    /**
     * @return \Magento\Eav\Model\Entity\Attribute
     */
    public function getSwatch()
    {
        return $this->_renderLayered;
    }

    public function checkIsActive($item, $requestVar)
    {
        $params = $this->_request->getParam($requestVar);
        $params = explode('_', $params);

        if (in_array($item->getValue(), $params)) {
            $item->setIsActive(true);
        }
    }
}
