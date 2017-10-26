<?php

namespace GoMage\Navigation\Plugin;

class SetTemplateNames {

    /**
     * @var \Magento\Framework\View\Element\Template\File\Resolver
     */
    protected $resolver;

    public function __construct(
        \Magento\Framework\View\Element\Template\File\Resolver $resolver
    ) {
        $this->resolver = $resolver;
    }

    /*public function aroundGetTemplateFile($subject, $template) {

        $template->par

        $data = $this->checkTemplateName('123');
        return ;
        if (empty($data)) {
            return $result;
        }

        $result = str_replace($data['magento'], $data['goMage'], $result);

        return $result;
    }*/

    protected function checkTemplateName($result)
    {
        $templates = [
            'Magento_Catalog::product/list/toolbar/viewmode.phtml' => 'GoMage_Navigation::product/list/toolbar/viewmode.phtml',
            'Magento_Catalog::product/list/toolbar/amount.phtml' => 'GoMage_Navigation::product/list/toolbar/amount.phtml',
            'Magento_Catalog::product/list/toolbar/limiter.phtml' => 'GoMage_Navigation::product/list/toolbar/limiter.phtml',
            'Magento_Catalog::product/list/toolbar/sorter.phtml' => 'GoMage_Navigation::product/list/toolbar/sorter.phtml'
        ];

        foreach ($templates as $magento => $goMage) {
            if (strpos($result, $magento)) {
                return ['magento' => $magento, 'goMage' => $goMage];
            }
        }

        return false;
    }
}