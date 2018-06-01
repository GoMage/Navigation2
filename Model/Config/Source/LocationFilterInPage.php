<?php
/**
 * Created by PhpStorm.
 * User: Димасик
 * Date: 08.03.2018
 * Time: 14:34
 */

namespace GoMage\Navigation\Model\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;

class LocationFilterInPage implements OptionSourceInterface
{
    /**
     * Get options
     *
     * @return array
     */
    public function toOptionArray()
    {
        return $options = [
            ['label' => 'Left Column', 'value' => 0],
            ['label' => 'Right Column', 'value' => 2],
            ['label' => 'Content', 'value' => 1],
        ];

    }
}