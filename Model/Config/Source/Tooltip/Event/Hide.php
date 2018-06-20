<?php

namespace GoMage\Navigation\Model\Config\Source\Tooltip\Event;

/**
 * Class Hide
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
