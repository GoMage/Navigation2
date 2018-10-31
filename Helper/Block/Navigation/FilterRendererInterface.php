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
namespace GoMage\Navigation\Helper\Block\Navigation;

use GoMage\Navigation\Model\Catalog\Layer\Filter\FilterInterface;

/**
 * Interface FilterRendererInterface
 */
interface FilterRendererInterface
{
    /**
     * Render filter
     *
     * @param  FilterInterface $filter
     * @return string
     */
    public function render(FilterInterface $filter);
}
