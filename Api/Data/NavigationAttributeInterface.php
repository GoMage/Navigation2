<?php


namespace GoMage\Navigation\Api\Data;

interface NavigationAttributeInterface
{

    const ID = 'id';

    /**
     * Get attribute_id
     * @return string|null
     */
    public function getId();

    /**
     * @param $id
     * @return mixed
     */
    public function setId($id);
}
