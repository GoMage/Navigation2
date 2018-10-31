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
 * Class Alignment
 *
 * @package GoMage\Navigation\Model\Config\Source
 */
class Alignment implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * @var int
     */
    const LEFT = 0;
    /**
     * @var int
     */
    const RIGHT = 1;
    /**
     * @var int
     */
    const TOP = 2;
    /**
     * @var int
     */
    const BOTTOM = 3;

    /**
     * @return array
     */
    public function toOptionArray()
    {
        return [
            ['value' => self::LEFT, 'label' => __('Left')],
            ['value' => self::RIGHT, 'label' => __('Right')],
            ['value' => self::TOP, 'label' => __('Top')],
            ['value' => self::BOTTOM, 'label' => __('Bottom')],
        ];
    }

    /**
     * @param $value
     * @return mixed
     * @throws \Exception
     */
    public function getAlignment($value)
    {
        $alignment[static::LEFT] = 'left';
        $alignment[static::RIGHT] = 'right';
        $alignment[static::TOP] = 'top';
        $alignment[static::BOTTOM] = 'bottom';

        if (empty($alignment[$value])) {
            throw new  \Magento\Framework\Exception\LocalizedException(
                __('Alignment position is not set for ' . (int) $value . ' type')
            );
        }

        return $alignment[$value];
    }
}
