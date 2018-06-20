<?php

namespace GoMage\Navigation\Model\Config\Source\Content\Filter;

/**
 * Class Type
 * @package GoMage\Navigation\Model\Config\Source\Content\Filter
 */
class Type implements \Magento\Framework\Option\ArrayInterface
{
    const ROWS = 0;
    const COLUMNS = 1;

    /**
     * @return array
     */
    public function toOptionArray()
    {
        return [
            ['value' => self::ROWS, 'label' => __('Rows')],
            ['value' => self::COLUMNS, 'label' => __('Columns')],
        ];
    }
}
