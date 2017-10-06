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
    protected $renderLayered;


    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Framework\Registry $coreRegistry
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Swatches\Block\LayeredNavigation\RenderLayered $renderLayered,
        array $data = array()
    )
    {
        $this->_coreRegistry = $coreRegistry;
        parent::__construct($context, $data);
        $this->renderLayered = $renderLayered;
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
        $this->_switchTemplate($filter);

        $filtersPreferred = [];
        $filtersCommon = [];
        $key = -1;

        foreach ($filter->getItems() as $filterItem) {
            $key++;

            if ($this->_isPreferred($filterItem)) {
                $filterItem->setIsPreferred(true);
                $filtersPreferred[$key] = $filterItem;
                continue;
            }

            $filtersCommon[$key] = $filterItem;
        }

        $filters = $filtersPreferred + $filtersCommon;
        $this->assign('filterItems', $filters);

        $html = $this->_toHtml();
        $this->assign('filterItems', []);
        return $html;
    }

    /**
     * @param \GoMage\Navigation\Model\Catalog\Layer\Filter\Item $filterItem
     * @return boolean
     */
    public function _isPreferred($filterItem)
    {
        if (is_null($this->_preferredItems)) {
            $this->_initPreferredFilterItems();
        }

        return in_array($filterItem->getValue(), $this->_preferredItems);
    }

    /**
     * @param FilterInterface $filter
     * @return $this
     */
    protected function _switchTemplate(FilterInterface $filter)
    {

        if ($filter->getSwatchInputType() == NavigationInterface::ATTRIBUTE_SWATCH_TEXT
            || $filter->getSwatchInputType() == NavigationInterface::ATTRIBUTE_SWATCH_VISUAL
        ) {
            $this->getSwatch()->setSwatchFilter($filter);
            $this->_filterType = $filter->getSwatchInputType();
            $template = 'GoMage_Navigation::layer/filter/swatches.phtml';
        } else {
            switch ($filter->getNavigation()) {
                case NavigationInterface::SLIDER:
                    $this->_filterType = NavigationInterface::TYPE_SLIDER;
                    $template = 'GoMage_Navigation::layer/filter/slider.phtml';
                    break;
                default:
                    $this->_filterType = NavigationInterface::TYPE_DEFAULTS;
                    $template = 'GoMage_Navigation::layer/filter/default.phtml';
            }
        }
        return $this->setTemplate($template);
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
        return $this->renderLayered;
    }

}
