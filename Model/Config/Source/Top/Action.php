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

namespace GoMage\Navigation\Model\Config\Source\Top;

/**
 * Class Action
 *
 * @package GoMage\Navigation\Model\Config\Source\Top
 */
class Action implements \Magento\Framework\Option\ArrayInterface
{
    const PAGE = 0;
    const PRODUCTS = 1;

    /**
     * @return array
     */
    public function toOptionArray()
    {
        return [
            ['value' => self::PAGE, 'label' => __('Back to top of page')],
            ['value' => self::PRODUCTS, 'label' => __('Back to top of products')],
        ];
    }
}
