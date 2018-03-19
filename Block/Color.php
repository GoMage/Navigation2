<?php
 
namespace GoMage\Navigation\Block;
 
use Magento\Framework\Registry;
 
class Color extends \Magento\Config\Block\System\Config\Form\Field
{

    /**
     * Color constructor.
     * @param \Magento\Backend\Block\Template\Context $context
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        array $data = []
    ) {
        parent::__construct($context, $data);
    }

    /**
     * @param \Magento\Framework\Data\Form\Element\AbstractElement $element
     * @return string
     */
    protected function _getElementHtml(\Magento\Framework\Data\Form\Element\AbstractElement $element)
    {
        $html = $element->getElementHtml();
        $value = $element->getData('value');

        $html .= '<script type="text/javascript">
            require(["jquery","jquery/colorpicker/js/colorpicker"], function ($) {
                $(document).ready(function () {
                    var $el = $("#' . $element->getHtmlId() . '");
                    $el.attr("maxlength", 7)
                    $el.on("input", function() {
                         var hex = $(this).val();
                         if(hex.length == 7) {
                            $el.css("backgroundColor", hex);
                         }
                        
                    });
                    $el.css("backgroundColor", "'. $value .'");
                    $el.ColorPicker({
                        color: "'. $value .'",
                        onChange: function (hsb, hex, rgb) {
                            $el.css("backgroundColor", "#" + hex).val("#" + hex);
                        },
                    }).bind("keyup", function(){
	                        $(this).ColorPickerSetColor("#"+this.value);
                        });
                });
            });
            </script>';

        return $html;
    }
}
