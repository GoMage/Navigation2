<?php

namespace GoMage\Navigation\Observer;

class GomageLoadAttribute implements \Magento\Framework\Event\ObserverInterface
{

    protected $navigationAttributeFactory;
    protected $navigationAttributeRepository;
    protected $navigationAttributeCollectionFactory;

    public function __construct(
        \GoMage\Navigation\Model\ResourceModel\NavigationAttribute\CollectionFactory $navigationAttributeCollectionFactory
    )
    {
        $this->navigationAttributeCollectionFactory = $navigationAttributeCollectionFactory;
    }

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