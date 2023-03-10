<?php

/**
 * GoMage.com
 *
 * GoMage Navigation M2
 *
 * @category  Extension
 * @copyright Copyright (c) 2018-2018 GoMage.com (https://www.gomage.com)
 * @author    GoMage.com
 * @license   https://www.gomage.com/licensing  Single domain license
 * @terms     of use https://www.gomage.com/terms-of-use
 * @version   Release: 1.1.0
 * @since     Class available since Release 1.0.0
 */

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
