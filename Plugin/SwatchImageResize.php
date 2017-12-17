<?php

namespace GoMage\Navigation\Plugin;

class SwatchImageResize
{
    protected $swatchImageTypes = ['swatch_image', 'swatch_thumb'];
    protected $request;
    protected $navigationAttributeCollectionFactory;

    public function __construct (
        \Magento\Framework\App\Helper\Context  $context,
        \GoMage\Navigation\Model\ResourceModel\NavigationAttribute\CollectionFactory $navigationAttributeCollectionFactory
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