<?php

namespace GoMage\Navigation\Model\Config\Source\Tooltip\Event;

/**
 * Class Show
 * @package GoMage\Navigation\Model\Config\Source\Tooltip\Event
 */
class Show implements \Magento\Framework\Option\ArrayInterface
{

    /**
     * @var int
     */
    const MOUSE_OVER = 0;
    /**
     * @var int
     */
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
