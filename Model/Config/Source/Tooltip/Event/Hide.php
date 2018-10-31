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
 * @version   Release: 2.0.0
 * @since     Class available since Release 2.0.0
 */

namespace GoMage\Navigation\Model\Config\Source\Tooltip\Event;

/**
 * Class Hide
 *
 * @package GoMage\Navigation\Model\Config\Source\Tooltip\Event
 */
class Hide implements \Magento\Framework\Option\ArrayInterface
{
    const MOUSE_OUT = 0;
    const CLOSE_BUTTON = 1;

    /**
     * @return array
     */
    public function toOptionArray()
    {
        return [
            ['value' => self::MOUSE_OUT, 'label' => __('Mouse Out')],
            ['value' => self::CLOSE_BUTTON, 'label' => __('Close Button')],
        ];
    }
}
