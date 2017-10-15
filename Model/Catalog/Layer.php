<?php

namespace GoMage\Navigation\Model\Catalog;

use Magento\Catalog\Api\CategoryRepositoryInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Catalog\Model\ResourceModel\Product\Attribute\CollectionFactory as AttributeCollectionFactory;

/**
 * Catalog view layer model
  */
class Layer extends  \Magento\Catalog\Model\Layer
{
    const FILTER_TYPE_DEFAULT         = 0;
    const FILTER_TYPE_IMAGE           = 1;
    const FILTER_TYPE_DROPDOWN        = 2;
    const FILTER_TYPE_INPUT           = 3;
    const FILTER_TYPE_SLIDER          = 4;
    const FILTER_TYPE_SLIDER_INPUT    = 5;
    const FILTER_TYPE_PLAIN           = 6;
    const FILTER_TYPE_FOLDING         = 7;
    const FILTER_TYPE_DEFAULT_PRO     = 8;
    const FILTER_TYPE_DEFAULT_INBLOCK = 9;
    const FILTER_TYPE_INPUT_SLIDER    = 10;
    const FILTER_TYPE_ACCORDION       = 11;
}
