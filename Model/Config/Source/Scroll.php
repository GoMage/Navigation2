<?php

namespace GoMage\Navigation\Model\Config\Source;

/**
 * Class Scroll
 *
 * @package GoMage\Navigation\Model\Config\Source
 */
class Scroll implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * @var int
     */
    const BUTTON = 0;
    /**
     * @var int
     */
    const AJAX = 1;

    /**
     * @return array
     */
    public function toOptionArray()
    {
        return [
            ['value' => self::BUTTON, 'label' => __('Button')],
            ['value' => self::AJAX, 'label' => __('Ajax')],
        ];
    }
}
