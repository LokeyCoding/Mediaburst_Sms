<?php
/**
 * MediaBurst SMS Magento Integration
 *
 * @category  Mage
 * @package   MediaBurst_Sms
 * @author    Lee Saferite <lee.saferite@lokeycoding.com>
 */

/**
 *
 */
class MediaBurst_Sms_Model_Mysql4_Message extends Mage_Core_Model_Mysql4_Abstract
{

    protected function _construct()
    {
        $this->_init('MediaBurst_Sms/Message', 'id');
    }

}