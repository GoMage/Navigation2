<?php
/**
 * Created by PhpStorm.
 * User: dimasik
 * Date: 1.9.18
 * Time: 9.20
 */

namespace GoMage\Navigation\Observer;

use Magento\Framework\Event\ObserverInterface;

class LimitationAfter implements ObserverInterface
{
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        //        $collection = $observer->getCollection();
        //        $T = $collection->getInitCategory();
        //        if($collection->getInitCategory()) {
        //            $collection->getSelect()->where('cat_index.category_id IN(?)',  implode(',', $collection->getInitCategory()));
        //            $t = $collection->getSelect()->__toString();
        //        }
    }
}