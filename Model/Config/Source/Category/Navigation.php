<?php

namespace GoMage\Navigation\Model\Config\Source\Category;

/**
 * Class Navigation
 * @package GoMage\Navigation\Model\Config\Source\Category
 */
class Navigation implements
    \Magento\Framework\Option\ArrayInterface,
    \GoMage\Navigation\Model\Config\Source\NavigationInterface
{

    /**
     * @return array
     */
    public function toOptionArray()
    {
        return [
            ['value' => self::DEFAULTS, 'label' => __('Default')],
            ['value' => self::FLY_OUT, 'label' => __('Fly-Out')],
            ['value' => self::IMAGE, 'label' => __('Image')],
            ['value' => self::DROP_DOWN, 'label' => __('Dropdown')],
            ['value' => self::IN_BLOCK, 'label' => __('In Block')],
        ];
    }
}
