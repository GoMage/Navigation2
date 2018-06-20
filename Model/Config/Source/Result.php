<?php

namespace GoMage\Navigation\Model\Config\Source;

/**
 * Class Result
 * @package GoMage\Navigation\Model\Config\Source
 */
class Result implements \Magento\Framework\Option\ArrayInterface
{

    /**
     * @var int
     */
    const NO = 0;
    /**
     * @var int
     */
    const YES = 1;
    /**
     * @var int
     */
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
