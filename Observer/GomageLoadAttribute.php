<?php

namespace GoMage\Navigation\Observer;

class GomageLoadAttribute implements \Magento\Framework\Event\ObserverInterface
{
    /**
     * @var \GoMage\Navigation\Model\ResourceModel\NavigationAttribute\CollectionFactory
     */
    protected $navigationAttributeCollectionFactory;

    /**
     * GomageLoadAttribute constructor.
     * @param \GoMage\Navigation\Model\ResourceModel\NavigationAttribute\CollectionFactory $navigationAttributeCollectionFactory
     */
    public function __construct(
        \GoMage\Navigation\Model\ResourceModel\NavigationAttribute\CollectionFactory $navigationAttributeCollectionFactory
    )
    {
        $this->navigationAttributeCollectionFactory = $navigationAttributeCollectionFactory;
    }

    /**
     * @param \Magento\Framework\Event\Observer $observer
     * @return $this
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $attribute = $observer->getEvent()->getAttribute();

        $navigationAttributeCollection = $this->navigationAttributeCollectionFactory->create()
            ->addFieldToFilter('attribute_id', $attribute->getId())
            ->setPageSize(1)
            ->load();

        $navigationAttribute = $navigationAttributeCollection->getFirstItem();

        if (!$navigationAttribute->getAttributeId()) {
            return $this;
        }

        foreach ($navigationAttribute->getData() as $field => $value) {
            $attribute->addData(['gomage_' . $field => $value]);
        }

        return $this;
    }
}