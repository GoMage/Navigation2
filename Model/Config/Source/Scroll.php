<?php

namespace GoMage\Navigation\Model\Config\Source;

class Scroll implements \Magento\Framework\Option\ArrayInterface
{

    const BUTTON = 0;
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
