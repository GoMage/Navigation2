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

namespace GoMage\Navigation\Observer;

use GoMage\Navigation\Model\NavigationAttributeRepository ;
use GoMage\Navigation\Model\ResourceModel\NavigationAttribute\CollectionFactory;

/**
 * Class SaveAttribute
 *
 * @package GoMage\Navigation\Observer
 */
class SaveAttribute implements \Magento\Framework\Event\ObserverInterface
{
    /**
     * @var \GoMage\Navigation\Model\NavigationAttributeRepository
     */
    protected $navigationAttributeRepository;

    /**
     * @var \GoMage\Navigation\Model\ResourceModel\NavigationAttribute\CollectionFactory
     */
    protected $navigationAttributeCollectionFactory;

    /**
     * @param NavigationAttributeRepository $navigationAttributeRepository
     * @param CollectionFactory $navigationAttributeCollectionFactory
     */
    public function __construct(
        NavigationAttributeRepository $navigationAttributeRepository,
        CollectionFactory $navigationAttributeCollectionFactory
    ) {
        $this->navigationAttributeRepository = $navigationAttributeRepository;
        $this->navigationAttributeCollectionFactory = $navigationAttributeCollectionFactory;
    }

    /**
     * @param \Magento\Framework\Event\Observer $observer
     * @return $this|void
     * @throws \Magento\Framework\Exception\CouldNotSaveException
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
            $navigationAttribute->setAttributeId($attribute->getId());
        }
        $navigationAttribute->addData(
            [
            'filter_type' => (int) $attribute->getData('gomage_filter_type'),
            'is_show_filter_button' => (int) $attribute->getData('gomage_is_show_filter_button'),
            'max_block_height' => (int) $attribute->getData('gomage_max_block_height'),
            'is_ajax' => (int) $attribute->getData('gomage_is_ajax'),
            'is_collapsed' => (int) $attribute->getData('gomage_is_collapsed'),
            'is_checkbox' => (int) $attribute->getData('gomage_is_checkbox'),
            'is_show_image_name' => (int) $attribute->getData('gomage_is_show_image_name'),
            'options_alignment' => (int) $attribute->getData('gomage_options_alignment'),
            'image_width' => (int) $attribute->getData('gomage_image_width'),
            'image_height' => (int) $attribute->getData('gomage_image_height'),
            'visible_options' => (int) $attribute->getData('gomage_visible_options'),
            'is_show_tooltip' => (int) $attribute->getData('gomage_is_show_tooltip'),
            'tooltip_width' => (int) $attribute->getData('gomage_tooltip_width'),
            'tooltip_height' => (int) $attribute->getData('gomage_tooltip_height'),
            'tooltip_text' => $this->prepareTooltipData($attribute->getData('gomage_tooltip_text')),
            'is_reset' => (int) $attribute->getData('gomage_is_reset'),
            'is_exclude_categories' =>  $attribute->getData('gomage_is_exclude_categories')
            ]
        );
        $this->navigationAttributeRepository->save($navigationAttribute);

        return $this;
    }

    /**
     * @param $data
     * @return string
     */
    protected function prepareTooltipData($data)
    {
        if (!is_array($data)) {
            return '';
        }

        foreach ($data as &$text) {
            $text = $text;
        }

        return serialize($data);
    }
}
