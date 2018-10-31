<?php
/**
 * Created by PhpStorm.
 * User: dimasik
 * Date: 17.10.18
 * Time: 15.31
 */

namespace GoMage\Navigation\Model\Layer\Filter\Item;


class DataBuilder extends \Magento\Catalog\Model\Layer\Filter\Item\DataBuilder
{
    public function addItemData($label, $value, $count, $parentCategory = null, $categoryId = null, $searchValue = null)
    {
        $this->_itemsData[] = [
            'id' => $categoryId,
            'parent_label' => $parentCategory,
            'label' => $label,
            'value' => $value,
            'count' => $count,
            'search_value' => $searchValue
        ];
    }
}