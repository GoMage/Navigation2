<?php

namespace GoMage\Navigation\Model\Config\Source;

/**
 * Class Place
 * @package GoMage\Navigation\Model\Config\Source
 */
class Place implements \Magento\Framework\Option\ArrayInterface
{

    /**
     * @var int
     */
    const LEFT_COLUMN = 0;
    /**
     * @var int
     */
    const CONTENT = 1;
    /**
     * @var int
     */
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
