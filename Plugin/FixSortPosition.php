<?php
/**
 * Created by PhpStorm.
 * User: Димасик
 * Date: 22.03.2018
 * Time: 7:23
 */

namespace GoMage\Navigation\Plugin;

use Magento\Catalog\Model\ResourceModel\Product\Collection as ProductCollection;

/**
 * Class FixSortPosition
 * @package GoMage\Navigation\Plugin
 */
class FixSortPosition
{
    /**
     * @param ProductCollection $subject
     * @param callable $proceed
     * @param $attribute
     * @param string $dir
     * @return ProductCollection
     */
    public function aroundAddAttributeToSort(ProductCollection $subject, callable $proceed, $attribute, $dir = ProductCollection::SORT_ORDER_ASC)
    {

        $proceed($attribute, $dir);

        // This bug only happens if an order clause is absent, thus only relevant if we're sorting by position.
        if ($attribute === 'position') {
            // Let's make sure we're not already sorting by e.entity_id
            $order = $subject->getSelect()->getPart('order');
            $found = array_filter($order, function($item) {
                if (isset($item[0]) && $item[0] === "e.entity_id") {
                    return true;
                }
                return false;
            });
            if (count($found) === 0) {
                $subject->getSelect()->order("e.entity_id {$dir}");
            }
        }

        return $subject;
    }
}