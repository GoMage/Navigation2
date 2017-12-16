<?php

namespace GoMage\Navigation\Plugin;


class VisualSwatch
{
    /**
     * @var \Magento\Framework\UrlInterface
     */
      protected $_urlBuilder;

    /**
     * @var \Magento\Framework\UrlInterface
     */
    protected $request;

    /**
     * VisualSwatch constructor.
     *
     * @param \Magento\Framework\UrlInterface
     */
      public function __construct(
          \Magento\Framework\View\Element\Context  $context
      ) {
            $this->request = $context->getRequest();
            $this->_urlBuilder = $context->getUrlBuilder();
      }

    public function aroundGetJsonConfig(
        \Magento\Swatches\Block\Adminhtml\Attribute\Edit\Options\Visual $subject,
        \Closure $proceed
    ) {
        $values = [];
        foreach ($subject->getOptionValues() as $value) {
            $values[] = $value->getData();
        }

        $data = [
            'attributesData' => $values,
            'uploadActionUrl' => $this->getUrl('swatches/iframe/show', [
                'attribute_id' => $this->request->get('attribute_id')
            ]),
            'isSortable' => (int)(!$subject->getReadOnly() && !$subject->canManageOptionDefaultOnly()),
            'isReadOnly' => (int)$subject->getReadOnly()
        ];

        return json_encode($data);
    }

    /**
     * Generate url by route and parameters
     *
     * @param   string $route
     * @param   array $params
     * @return  string
     */
    public function getUrl($route = '', $params = [])
    {
        return $this->_urlBuilder->getUrl($route, $params);
    }
}