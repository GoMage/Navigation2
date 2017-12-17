<?php
/**
 * Created by PhpStorm.
 * User: Димасик
 * Date: 17.12.2017
 * Time: 20:28
 */

namespace GoMage\Navigation\Model;

class Saveimage extends \Magento\Config\Model\Config\Backend\Image
{
    /**
     * The tail part of directory path for uploading
     */

    const IMAGE_DIR = 'gomage/images';

    /**
     *
     * @var int
     */
    protected $_maxFileSize = 2048;

    /**
     *
     * @return string
     */
    protected function _getUploadDir()
    {
        return $this->_mediaDirectory->getAbsolutePath($this->_appendScopeInfo(self::IMAGE_DIR));
    }

    /**
     *
     * @return boolean
     */
    protected function _addWhetherScopeInfo()
    {
        return true;
    }

    /**
     *
     * @return $this
     */
    public function beforeSave()
    {
        $value = $this->getValue();
        $deleteFlag = is_array($value) && !empty($value['delete']);
        $fileTmpName = $_FILES['groups']['tmp_name'][$this->getGroupId()]['fields'][$this->getField()]['value'];

        if ($this->getOldValue() && ($fileTmpName || $deleteFlag)) {
            $this->_mediaDirectory->delete(self::IMAGE_DIR . '/' . $this->getOldValue());
        }
        return parent::beforeSave();
    }
}