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
 * @version   Release: 1.0.0
 * @since     Class available since Release 1.0.0
 */

namespace GoMage\Navigation\Model\Config\Source\Category;

/**
 * Class Navigation
 *
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
