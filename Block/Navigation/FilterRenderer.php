<?php

namespace GoMage\Navigation\Block\Navigation;

use GoMage\Navigation\Model\Catalog\Layer\Filter\FilterInterface;
use Magento\Framework\View\Element\Template;
use GoMage\Navigation\Model\Config\Source\NavigationInterface;

class FilterRenderer extends Template implements FilterRendererInterface
{
    /**
     * @param FilterInterface $filter
     * @return string
     */
    public function render(FilterInterface $filter)
    {
        $this->_switchTemplate($filter);

        $this->assign('filterItems', $filter->getItems());
        $html = $this->_toHtml();
        $this->assign('filterItems', []);
        return $html;
    }

    /**
     * @param FilterInterface $filter
     * @return $this
     */
    protected function _switchTemplate(FilterInterface $filter)
    {
        switch ($filter->getNavigation()) {
            case NavigationInterface::DROP_DOWN:
                $template = 'layer/filter/drop_down.phtml';
                break;
            default:
                $template = 'layer/filter/default.phtml';
        }

        return $this->setTemplate($template);
    }

}
