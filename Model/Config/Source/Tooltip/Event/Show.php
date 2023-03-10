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

namespace GoMage\Navigation\Model\Config\Source\Tooltip\Event;

/**
 * Class Show
 *
 * @package GoMage\Navigation\Model\Config\Source\Tooltip\Event
 */
class Show implements \Magento\Framework\Option\ArrayInterface
{
    const MOUSE_OVER = 0;
    const CLICK = 1;

    /**
     * @return array
     */
    public function toOptionArray()
    {
        return [
            ['value' => self::MOUSE_OVER, 'label' => __('Mouse Over')],
            ['value' => self::CLICK, 'label' => __('Click')],
        ];
    }
}
