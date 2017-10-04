<?php

namespace GoMage\Navigation\Observer;

class GoMageExtendCatalogProductAttributeEditForm implements \Magento\Framework\Event\ObserverInterface
{
    public function execute(\Magento\Framework\Event\Observer $observer)
    {


        $form = $observer->getData('form');

        $fieldset = $form->addFieldset(
            'advanced_navigation_fieldset',
            ['legend' => __('GoMage Advanced Navigation Properties'), 'collapsable' => true]
        );

        $fieldset->addField(
            'is_checkbox',
            'select',
            [
                'name'   => 'is_checkbox',
                'label'  => __('Show Checkboxes'),
                'title'  => __('Show Checkboxes'),
                'values' => [1,2],
            ]
        );

        return $this;
    }
}