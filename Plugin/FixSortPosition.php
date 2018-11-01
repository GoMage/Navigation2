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

namespace GoMage\Navigation\Plugin;

use Magento\Catalog\Model\ResourceModel\Product\Collection as ProductCollection;

/**
 * Class FixSortPosition
 *
 * @package GoMage\Navigation\Plugin
 */
class FixSortPosition
{

    public function aroundAddAttributeToSort(
        ProductCollection $subject,
        callable $proceed,
        $attribute,
        $dir = ProductCollection::SORT_ORDER_ASC
    ) {

        $proceed($attribute, $dir);

        // This bug only happens if an order clause is absent, thus only relevant if we're sorting by position.
        if ($attribute === 'position') {
            // Let's make sure we're not already sorting by e.entity_id
            $order = $subject->getSelect()->getPart('order');
            $found = array_filter(
                $order,
                function ($item) {
                    if (isset($item[0]) && $item[0] === "e.entity_id") {
                        return true;
                    }
                    return false;
                }
            );
            if (count($found) === 0) {
                $subject->getSelect()->order("e.entity_id {$dir}");
            }
        }

        return $subject;
    }
}