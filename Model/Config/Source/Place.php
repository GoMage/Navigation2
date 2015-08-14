<?php

namespace GoMage\Navigation\Model\Config\Source;

class Place implements \Magento\Framework\Option\ArrayInterface
{

    const LEFT_COLUMN = 0;
    const CONTENT = 1;
    const RIGHT_COLUMN = 2;

    /**
     * @return array
     */
    public function toOptionArray()
    {
        return [
            ['value' => self::LEFT_COLUMN, 'label' => __('Left Column')],
            ['value' => self::CONTENT, 'label' => __('Content')],
            ['value' => self::RIGHT_COLUMN, 'label' => __('Right Column')],
        ];
    }
}
