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
 * @version   Release: 1.1.0
 * @since     Class available since Release 1.0.0
 */

namespace GoMage\Navigation\Model\Config\Source;

/**
 * Class Result
 *
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
