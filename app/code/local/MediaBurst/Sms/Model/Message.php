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
class MediaBurst_Sms_Model_Message extends Mage_Core_Model_Abstract
{
    const STATUS_PENDING = 0;
    const STATUS_SENT = 1;
    const STATUS_FAILED = 2;

    protected function _construct()
    {
        $this->_init('MediaBurst_Sms/Message');
    }

}