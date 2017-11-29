<?php

namespace GoMage\Navigation\Observer;

class LoadAttribute implements \Magento\Framework\Event\ObserverInterface
{
    /**
     * @var \GoMage\Navigation\Model\ResourceModel\NavigationAttribute\CollectionFactory
     */
    protected $navigationAttributeCollectionFactory;

    /**
     * @var \Magento\Catalog\Model\Session
     */
    protected $catalogSession;

    /**
     * GomageLoadAttribute constructor.
     * @param \GoMage\Navigation\Model\ResourceModel\NavigationAttribute\CollectionFactory $navigationAttributeCollectionFactory
     * @param \Magento\Catalog\Model\Session $catalogSession
     */
    public function __construct(
        \GoMage\Navigation\Model\ResourceModel\NavigationAttribute\CollectionFactory $navigationAttributeCollectionFactory,
        \Magento\Catalog\Model\Session $catalogSession
    ) {
    
        $this->navigationAttributeCollectionFactory = $navigationAttributeCollectionFactory;
        $this->catalogSession = $catalogSession;
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

        $goMageAttributeSettings = $this->getSettingsArray();

        foreach ($navigationAttribute->getData() as $field => $value) {
            $temp = ['gomage_' . $field => $value];
            $attribute->addData($temp);
            $goMageAttributeSettings[$attribute->getId()]['gomage_' . $field] = $value;
        }

        $this->catalogSession->setGoMageAttributeSettings($goMageAttributeSettings);

        return $this;
    }

    /**
     * @return array
     */
    protected function getSettingsArray()
    {
        $goMageAttributeSettings = $this->catalogSession->getGoMageAttributeSettings();
        if (!is_array($goMageAttributeSettings)) {
            return $goMageAttributeSettings = [];
        }

        return $goMageAttributeSettings;
    }
}