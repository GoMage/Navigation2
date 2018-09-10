<?php

namespace GoMage\Navigation\Model\Config\Source;

/**
 * Class Navigation
 *
 * @package GoMage\Navigation\Model\Config\Source
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
            ['value' => self::COLOR_PICKER, 'label' => __('Color Picker')],
            ['value' => self::DROP_DOWN, 'label' => __('Dropdown')],
            ['value' => self::IN_BLOCK, 'label' => __('In Block')],
            ['value' => self::BUTTON, 'label' => __('Swatch')],
            ['value' => self::INPUT, 'label' => __('Input')],
            ['value' => self::SLIDER, 'label' => __('Slider')],
            ['value' => self::SLIDER_INPUT, 'label' => __('Slider & Input')],
        ];
    }
}
