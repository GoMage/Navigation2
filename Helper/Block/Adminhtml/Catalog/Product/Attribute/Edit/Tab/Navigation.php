<?php

namespace GoMage\Navigation\Block\Adminhtml\Catalog\Product\Attribute\Edit\Tab;

use Magento\Backend\Block\Widget\Form\Generic;
use Magento\Config\Model\Config\Source\Yesno;
use GoMage\Navigation\Model\Config\Source\Navigation as SourceNavigation;
use GoMage\Navigation\Model\Config\Source\Image\Alignment;

class Navigation extends Generic
{
    /**
     * @var Yesno
     */
    protected $yesNo;

    /**
     * @var SourceNavigation
     */
    protected $navigation;

    /**
     * @var Alignment
     */
    protected $alignment;

    /**
     * Navigation constructor.
     *
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Registry             $registry
     * @param \Magento\Framework\Data\FormFactory     $formFactory
     * @param Yesno                                   $yesNo
     * @param SourceNavigation                        $navigation
     * @param Alignment                               $alignment
     * @param array                                   $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        Yesno $yesNo,
        SourceNavigation $navigation,
        Alignment $alignment,
        array $data = []
    ) {
        $this->yesNo      = $yesNo;
        $this->navigation = $navigation;
        $this->alignment  = $alignment;
        parent::__construct($context, $registry, $formFactory, $data);
    }

    /**
     * Adding product form elements for editing attribute
     *
     * @return                  $this
     * @SuppressWarnings(PHPMD)
     */
    protected function _prepareForm()
    {
        $attributeObject = $this->getAttributeObject();

        $form = $this->_formFactory->create(
            ['data' => ['id' => 'edit_form', 'action' => $this->getData('action'), 'method' => 'post']]
        );

        $fieldset = $form->addFieldset(
            'navigation_fieldset',
            ['legend' => __('Advanced Navigation Properties'), 'collapsable' => true]
        );

        $yesno = $this->yesNo->toOptionArray();

        $fieldset->addField(
            'navigation',
            'select',
            [
                'name'   => 'navigation',
                'label'  => __('Filter Type'),
                'title'  => __('Filter Type'),
                'values' => $this->navigation->toOptionArray(),
            ]
        );


        $fieldset->addField(
            'is_show_button',
            'select',
            [
                'name'   => 'is_show_button',
                'label'  => __('Show Filter Button'),
                'title'  => __('Show Filter Button'),
                'values' => $yesno,
            ]
        );

        $fieldset->addField(
            'is_ajax',
            'select',
            [
                'name'   => 'is_ajax',
                'label'  => __('Use Ajax'),
                'title'  => __('Use Ajax'),
                'values' => $yesno,
                'value'  => $attributeObject->getData('is_ajax'),
            ]
        );

        $fieldset->addField(
            'is_collapsed',
            'select',
            [
                'name'   => 'is_collapsed',
                'label'  => __('Show Collapsed'),
                'title'  => __('Show Collapsed'),
                'values' => $yesno,
            ]
        );

        $fieldset->addField(
            'is_checkbox',
            'select',
            [
                'name'   => 'is_checkbox',
                'label'  => __('Show Checkboxes'),
                'title'  => __('Show Checkboxes'),
                'values' => $yesno,
            ]
        );

        $fieldset->addField(
            'is_image',
            'select',
            [
                'name'   => 'is_image',
                'label'  => __('Show Image'),
                'title'  => __('Show Image'),
                'values' => $yesno,
            ]
        );

        $fieldset->addField(
            'imagealignment',
            'select',
            [
                'name'   => 'imagealignment',
                'label'  => __('Image Alignment'),
                'title'  => __('Image Alignment'),
                'values' => $this->alignment->toOptionArray(),
            ]
        );

        $fieldset->addField(
            'image_width',
            'text',
            [
                'name'  => 'image_width',
                'label' => __('Image Width, px'),
                'title' => __('Image Width, px'),
                'class' => 'validate-number'
            ]
        );

        $fieldset->addField(
            'image_height',
            'text',
            [
                'name'  => 'image_height',
                'label' => __('Image Height, px'),
                'title' => __('Image Height, px'),
                'class' => 'validate-number'
            ]
        );

        $fieldset->addField(
            'limit',
            'text',
            [
                'name'  => 'limit',
                'label' => __('Visible Options per Attribute'),
                'title' => __('Visible Options per Attribute'),
                'class' => 'validate-number'
            ]
        );

        $fieldset->addField(
            'is_tooltip',
            'select',
            [
                'name'   => 'is_tooltip',
                'label'  => __('Show Tooltip'),
                'title'  => __('Show Tooltip'),
                'values' => $yesno,
            ]
        );

        $fieldset->addField(
            'tooltip_width',
            'text',
            [
                'name'  => 'tooltip_width',
                'label' => __('Tooltip Window Width, px'),
                'title' => __('Tooltip Window Width, px'),
                'class' => 'validate-number'
            ]
        );

        $fieldset->addField(
            'tooltip_height',
            'text',
            [
                'name'  => 'tooltip_height',
                'label' => __('Tooltip Window Height, px'),
                'title' => __('Tooltip Window Height, px'),
                'class' => 'validate-number'
            ]
        );

        $fieldset->addField(
            'tooltip_text',
            'textarea',
            [
                'name'  => 'tooltip_text',
                'label' => __('Tooltip Text'),
                'title' => __('Tooltip Text'),
            ]
        );

        $fieldset->addField(
            'is_reset',
            'select',
            [
                'name'   => 'is_reset',
                'label'  => __('Show Reset Link'),
                'title'  => __('Show Reset Link'),
                'values' => $yesno,
            ]
        );

        $this->_eventManager->dispatch(
            'adminhtml_catalog_product_attribute_editnavigation_prepare_form',
            ['form' => $form, 'attribute' => $attributeObject]
        );

        $this->setForm($form);
        return $this;
    }

    /**
     * Initialize form fields values
     *
     * @return $this
     */
    protected function _initFormValues()
    {
        $this->getForm()->addValues($this->getAttributeObject()->getData());
        return parent::_initFormValues();
    }

    /**
     * Retrieve attribute object from registry
     *
     * @return mixed
     */
    private function getAttributeObject()
    {
        return $this->_coreRegistry->registry('entity_attribute');
    }
}
