<?php

/**
 * GoMage.com
 *
 * GoMage Navigation M2
 *
 * @category  Extension
 * @copyright Copyright (c) 2018-2018 GoMage.com (https://www.gomage.com)
 * @author    GoMage.com
 * @license   https://www.gomage.com/licensing  Single domain license
 * @terms     of use https://www.gomage.com/terms-of-use
 * @version   Release: 1.0.0
 * @since     Class available since Release 1.0.0
 */

namespace GoMage\Navigation\Model\Config\Source\Content\Filter;

/**
 * Class Type
 *
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
