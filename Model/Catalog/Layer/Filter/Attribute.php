<?php

namespace GoMage\Navigation\Model\Catalog\Layer\Filter;

use GoMage\Navigation\Model\Config\Source\NavigationInterface;

class Attribute extends \Magento\Catalog\Model\Layer\Filter\Attribute implements FilterInterface
{

    /**
     * {@inheritdoc}
     */
    public function getNavigation()
    {
        $navigation = $this->getAttributeModel()->getNavigation();

        switch ($navigation) {
            case NavigationInterface::DEFAULTS:
            case NavigationInterface::COLOR_PICKER:
            case NavigationInterface::DROP_DOWN:
            case NavigationInterface::IN_BLOCK:
            case NavigationInterface::BUTTON:
            case NavigationInterface::INPUT:
            case NavigationInterface::SLIDER:
            case NavigationInterface::SLIDER_INPUT:
                return $navigation;
                break;
            default:
                throw new \LogicException('Undefined navigation type');
        }

    }

    /**
     * {@inheritdoc}
     */
    public function isAjax()
    {
        return boolval($this->getAttributeModel()->getIsAjax());
    }
}
