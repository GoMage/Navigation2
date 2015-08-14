<?php

namespace GoMage\Navigation\Model\Config\Source;

class Result implements \Magento\Framework\Option\ArrayInterface
{

    const NO = 0;
    const YES = 1;
    const REMOVE = 2;

    /**
     * @return array
     */
    public function toOptionArray()
    {
        return [
            ['value' => self::YES, 'label' => __('Yes')],
            ['value' => self::REMOVE, 'label' => __('Yes, remove value from list')],
            ['value' => self::NO, 'label' => __('No')],
        ];
    }
}
