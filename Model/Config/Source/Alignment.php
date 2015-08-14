<?php

namespace GoMage\Navigation\Model\Config\Source;

class Alignment implements \Magento\Framework\Option\ArrayInterface
{
    const LEFT = 0;
    const RIGHT = 1;
    const TOP = 2;
    const BOTTOM = 3;

    /**
     * @return array
     */
    public function toOptionArray()
    {
        return [
            ['value' => self::LEFT, 'label' => __('Left')],
            ['value' => self::RIGHT, 'label' => __('Right')],
            ['value' => self::TOP, 'label' => __('Top')],
            ['value' => self::BOTTOM, 'label' => __('Bottom')],
        ];
    }
}
