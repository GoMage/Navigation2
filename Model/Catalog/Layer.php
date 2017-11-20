<?php

namespace GoMage\Navigation\Model\Catalog;

use Magento\Catalog\Api\CategoryRepositoryInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Catalog\Model\ResourceModel\Product\Attribute\CollectionFactory as AttributeCollectionFactory;

/**
 * Catalog view layer model
 */
class Layer extends \Magento\Catalog\Model\Layer
{
    const FILTER_TYPE_DEFAULT            = 0;
    const FILTER_TYPE_COLOR_PICKER       = 1;
    const FILTER_TYPE_DROPDOWN           = 2;
    const FILTER_TYPE_IN_BLOCK           = 3;
    const FILTER_TYPE_BUTTON             = 4;
    const FILTER_TYPE_INPUT              = 5;
    const FILTER_TYPE_SLIDER             = 6;
    const FILTER_TYPE_SLIDER_AND_INPUT   = 7;
}
