<?php
/**
 * MediaBurst SMS Magento Integration
 *
 * @category  Mage
 * @package   MediaBurst_Sms
 * @author    Lee Saferite <lee.saferite@lokeycoding.com>
 */

/**
 * Grid Container
 */
class MediaBurst_Sms_Block_GridContainer extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    protected function _prepareLayout()
    {
        Mage_Adminhtml_Block_Widget_Container::_prepareLayout();
    }

    public function setHeaderText($headerText)
    {
        $this->_headerText = $headerText;
    }
}