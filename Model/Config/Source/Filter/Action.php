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

namespace GoMage\Navigation\Model\Config\Source\Filter;

/**
 * Class Action
 *
 * @package GoMage\Navigation\Model\Config\Source\Filter
 */
class Action implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * @var int
     */
    const PAGE_RELOAD = 0;
    /**
     * @var int
     */
    const AJAX = 1;

    /**
     * @return array
     */
    public function toOptionArray()
    {
        return [
            ['value' => self::PAGE_RELOAD, 'label' => __('Page Reload (Breadcrumbs)')],
            ['value' => self::AJAX, 'label' => __('Ajax')],
        ];
    }
}
