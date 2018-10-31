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
 * @version   Release: 2.0.0
 * @since     Class available since Release 2.0.0
 */

namespace GoMage\Navigation\Model\Config\Source\Image;

/**
 * Class Alignment
 *
 * @package GoMage\Navigation\Model\Config\Source\Image
 */
class Alignment implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * @var int
     */
    const VERTICALLY = 0;
    /**
     * @var int
     */
    const HORIZONTALLY = 1;
    /**
     * @var int
     */
    const TWO_COLUMNS = 2;

    /**
     * @return array
     */
    public function toOptionArray()
    {
        return [
            ['value' => self::VERTICALLY, 'label' => __('Vertically')],
            ['value' => self::HORIZONTALLY, 'label' => __('Horizontally')],
            ['value' => self::TWO_COLUMNS, 'label' => __('2 Columns')],
        ];
    }
}
