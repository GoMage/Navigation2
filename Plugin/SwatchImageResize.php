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

namespace GoMage\Navigation\Plugin;

use Magento\Framework\App\Helper\Context;
use GoMage\Navigation\Model\ResourceModel\NavigationAttribute\CollectionFactory;

class SwatchImageResize
{
    protected $swatchImageTypes = ['swatch_image', 'swatch_thumb'];
    protected $request;
    protected $navigationAttributeCollectionFactory;

    /**
     * @param Context  $context
     * @param CollectionFactory $navigationAttributeCollectionFactory
     */
    public function __construct(
        Context $context,
        CollectionFactory $navigationAttributeCollectionFactory
    ) {
        $this->request = $context->getRequest();
        $this->navigationAttributeCollectionFactory = $navigationAttributeCollectionFactory;
    }

    /**
     * @param $swatchelperMedia
     * @param $result
     * @return array
     */
    public function afterGetImageConfig(\Magento\Swatches\Helper\Media $subject, $result)
    {
        $navigationAttributeCollection = $this->navigationAttributeCollectionFactory->create()
            ->addFieldToFilter('attribute_id', (int) $this->request->get('attribute_id'))
            ->setPageSize(1)
            ->load();
        $navigationAttribute = $navigationAttributeCollection->getFirstItem();

        if (!$navigationAttribute->getAttributeId()) {
            return $result;
        }
        foreach ($this->swatchImageTypes as $swatchType) {
             $result[$swatchType]['width'] = $navigationAttribute->getImageWidth();
             $result[$swatchType]['height'] = $navigationAttribute->getImageHeight();
        }

        return $result;
    }
}
