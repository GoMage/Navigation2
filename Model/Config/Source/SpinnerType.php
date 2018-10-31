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
 * @version   Release: 2.0.0
 * @since     Class available since Release 2.0.0
 */

namespace GoMage\Navigation\Model\Config\Source;

/**
 * Class SpinnerType
 *
 * @package GoMage\Navigation\Model\Config\Source
 */
class SpinnerType implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * @var int
     */
    const STANDARD = 0;
    /**
     * @var int
     */
    const IMAGE = 1;

    /**
     * @return array
     */
    public function toOptionArray()
    {
        return [
            ['value' => self::STANDARD, 'label' => __('Standard')],
            ['value' => self::IMAGE, 'label' => __('Image')],
        ];
    }
}
